<?php
require_once __DIR__ . "/../config/database.php";

    function removeSession() {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_unset();
        session_destroy();
        setcookie("user_session", "", time() + 0, "/");
    }

    function deleteCookie($userID) {
        global $connection;

        $sql = "DELETE FROM USER_SESSION WHERE User_ID = ?";

        if(!$connection) return false;

        try {
            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Failed To Connect to DB: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $userID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Executing Statement failed: " . mysqli_error($connection));
            }
 
            mysqli_stmt_close($stmt);
        } catch(Exception $e) {
            echo "#001 - Something went wrong. Please try again later.";
        }
    }

    function isValidSession($userID, $cookie) {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        global $connection;

        $sql = "SELECT Token, Expire_Date FROM USER_SESSION WHERE User_ID = ? and Expire_Date > NOW()";

        if(!$connection) return false;

        try {
            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $userID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Executing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if(!$row || !hash_equals($row['Token'], $cookie)) {
                deleteCookie($userID);
                removeSession();
                header('Location: index.php');
                exit;
            }
            
            return true;
        } catch(Exception $e) {
            echo "#002 - Something went wrong. Please try again later.";
            deleteCookie($userID);
            removeSession();
            header('Location: index.php');
            exit;
        }
    

    }

    function createPatientID($userID, $allergies = null) {
        global $connection;

        $sql = "INSERT INTO PATIENTS(User_ID, Allergies) VALUES (?,?)";

        if (!$connection) {
            return false;
        }

        if($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, 'is', $userID, $allergies);

            $result = mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);

            return $result;
        } else {
            echo '#003 - Something went wrong. Please try again later.';
            return false;
        }

    }
    
    function getPatientID($userID) {

        global $connection;
        $id = null;

        $sql = "SELECT ID FROM PATIENTS WHERE User_ID = ?";

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, 'i', $userID);

            if(!mysqli_stmt_execute($stmt)) {
                echo 'Error';
                mysqli_stmt_close($stmt);
                return null;
            }

            mysqli_stmt_bind_result($stmt, $id);

            if(mysqli_stmt_fetch($stmt)) {
                mysqli_stmt_close($stmt);
                return $id;
            }

               mysqli_stmt_close($stmt);
               if(createPatientID($userID)) {
                return getPatientID($userID);
               } else {
                return null;   
               }

        } else {
            echo '#004 - Something went wrong. Please try again later.';
            return null;
        }

    }

    function createBillByAppointmentID($appointmentID, $amount) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'INSERT INTO BILLING(Appointment_ID, Amount, isPaid)
                    VALUES (?, ?, NULL)';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "ii", $appointmentID, $amount);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_insert_id($connection);

            mysqli_stmt_close($stmt);

            return $result;

        } catch(Exception $e) {
            echo "#005 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function removeBillByAppointmentID($appointmentID) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'DELETE FROM BILLING WHERE Appointment_ID = ?';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $appointmentID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_close($stmt);

            return true;

        } catch(Exception $e) {
            echo "#006 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function confirmBillByAppointmentID($appointmentID) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'UPDATE BILLING SET isPaid = CURRENT_DATE() WHERE Appointment_ID = ?';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $appointmentID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_close($stmt);

            return true;

        } catch(Exception $e) {
            echo "#007 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function createAppointment($patientID, $time, $date, $visitReason) { 

        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'INSERT INTO APPOINTMENTS(Doctor_ID, Patient_ID, Date, Time_ID, Visit_Reason, Status_ID)
                    VALUES (1, ?, ?, ?, ?, 1)';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "isis", $patientID, $date, $time, $visitReason);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_insert_id($connection);

            mysqli_stmt_close($stmt);

            createBillByAppointmentID($result, 1500);

            return $result;

        } catch(Exception $e) {
            echo "#008 - Something went wrong. Please try again later."; 
            return false;
        }

    }
 
    function getAppointmentsByUserID($userID) {
        global $connection;
        $appointments = [];   
        $timeMap = [
            1 => "08:00",
            2 => "08:30",
            3 => "09:00",
            4 => "09:30",
            5 => "10:00",
            6 => "10:30",
            7 => "11:00",
            8 => "11:30",
            9 => "14:00",
            10 => "14:30",
            11 => "15:00",
            12 => "15:30",
            13 => "16:00"
        ];

        if (!$connection) {
            echo 'No Connection Found!';
            return;
        }

        try {

            $sql = "SELECT A.ID, A.Date, A.Time_ID, A.Visit_Reason, A.Status_ID,  CONCAT(P.First_Name, ' ', P.Last_Name) AS Doctor_Name
                    FROM USERS U JOIN PATIENTS T
                    ON U.ID = T.User_ID
                    JOIN APPOINTMENTS A
                    ON T.ID = A.Patient_ID JOIN DOCTORS D
                    ON A.Doctor_ID = D.ID JOIN PERSONS P
                    ON D.User_ID = P.User_ID
                    WHERE U.ID = ? ORDER BY A.DATE ASC, A.TIME_ID ASC";
                
            if (!($stmt = mysqli_prepare($connection, $sql))) {
                    throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }
                
            mysqli_stmt_bind_param($stmt, "i", $userID);

            if (!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Executing Statement failed: " . mysqli_error($connection));
            }
            
            $result = mysqli_stmt_get_result($stmt);

            
            while ($row = mysqli_fetch_assoc($result)) { 
                $row['Time'] = $timeMap[$row['Time_ID']];
                $appointments[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            return $appointments;
        } catch (Exception $e) {
            echo "#009 - Something went wrong. Please try again later."; 
        }


    }

    function getAppointmentsByDoctorID($doctorID) {
        global $connection;
        $appointments = [];   
        $timeMap = [
            1 => "08:00",
            2 => "08:30",
            3 => "09:00",
            4 => "09:30",
            5 => "10:00",
            6 => "10:30",
            7 => "11:00",
            8 => "11:30",
            9 => "14:00",
            10 => "14:30",
            11 => "15:00",
            12 => "15:30",
            13 => "16:00"
        ];

        if (!$connection) {
            echo 'No Connection Found!';
            return;
        }

        try {

            $sql = "SELECT A.ID, A.Date, A.Time_ID, A.Visit_Reason, A.Status_ID, PE.First_Name, PE.Last_Name, 
                    PE.Gender, PE.Birth_Date, TIMESTAMPDIFF(YEAR, PE.Birth_Date, CURDATE()) AS Age, Phone_Number
                    FROM DOCTORS D JOIN APPOINTMENTS A
                    ON D.ID = A.Doctor_ID JOIN PATIENTS P
                    ON A.Patient_ID = P.ID JOIN PERSONS PE
                    ON P.User_ID = PE.User_ID
                    WHERE D.ID = ? ORDER BY A.DATE ASC, A.TIME_ID ASC";
                
            if (!($stmt = mysqli_prepare($connection, $sql))) {
                    throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }
                
            mysqli_stmt_bind_param($stmt, "i", $doctorID);

            if (!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Executing Statement failed: " . mysqli_error($connection));
            }
            
            $result = mysqli_stmt_get_result($stmt);

            
            while ($row = mysqli_fetch_assoc($result)) { 
                $row['Time'] = $timeMap[$row['Time_ID']];
                $row['Name'] = $row["First_Name"] . " " . $row["Last_Name"];
                $appointments[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            return $appointments;
        } catch (Exception $e) {
            error_log($e->getMessage()); 
            echo "#010 - Something went wrong. Please try again later."; 
        }
    }

    function removeAppointmentByID($appointID) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'UPDATE APPOINTMENTS SET Status_ID = 3 WHERE ID = ?';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $appointID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_insert_id($connection);

            mysqli_stmt_close($stmt);

            removeBillByAppointmentID($appointID);

            return $result;

        } catch(Exception $e) {
            echo "#011 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function confirmAppointmentByID($appointID) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'UPDATE APPOINTMENTS SET Status_ID = 2 WHERE ID = ?';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "i", $appointID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_insert_id($connection);

            mysqli_stmt_close($stmt);

            confirmBillByAppointmentID($appointID);

            return $result;

        } catch(Exception $e) {
            echo "#012 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function changeAppointementStatus($appointID, $status) {
        global $connection;

        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = 'UPDATE APPOINTMENTS SET Status_ID = ?  WHERE ID = ?';

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, "ii", $status, $appointID);

            if(!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_close($stmt);

            return true;

        } catch(Exception $e) {
            echo "#013 - Something went wrong. Please try again later."; 
            return false;
        }
    }

    function isPersonExist($userID) {
        global $connection;

        if(!$connection) {
            die("Connection failed to the server");
        }

        try {
            $sql = "SELECT ID, First_Name, Last_Name FROM PERSONS WHERE User_ID = ?";

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, 'i', $userID);

            if(!(mysqli_stmt_execute($stmt))) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if($row) {
                $_SESSION['person_name'] = $row['First_Name'] . ' ' . $row['Last_Name'];
            }

            return $row;
            
        } catch(Exception $e) {
            echo "#014 - Something went wrong. Please try again later."; 
            return false;
        }
    }
   
    function createPerson($userID, $firstName, $lastName, $dob, $gender, $phone) {
        global $connection;

        if(!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $sql = "INSERT INTO PERSONS(First_Name, Last_Name, Birth_Date, Gender, Phone_Number, User_ID)
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, 'sssssi', $firstName, $lastName, $dob, $gender, $phone, $userID);

            $result = mysqli_stmt_execute($stmt);
            
            mysqli_stmt_close($stmt);

            return $result;
                
        } catch(Exception $e) {
            echo "Something went wrong. Please try again later."; 
            return false;
        }
    }

    function isDoctor($userID) {
        global $connection;

        if(!$connection) {
            die("Connection failed to the server");
        }

        try {
            $sql = " SELECT D.ID FROM USERS U 
                     JOIN DOCTORS D ON U.ID = D.User_ID
                     WHERE U.ID = ? and U.Role_ID = 2";

            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            mysqli_stmt_bind_param($stmt, 'i', $userID);

            if(!(mysqli_stmt_execute($stmt))) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }

            $result = mysqli_stmt_get_result($stmt);

            $row = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);
            
            if($row) {
                $_SESSION['doctor_id'] = $row['ID'];
            }

            return $row;

        } catch(Exception $e) {
            echo "#015 - Something went wrong. Please try again later."; 
            return false;
        }
    }


    function getInvalidTimeByDate($date) {
        try {

            global $connection;
        
            if(!$connection) {
                die("Connection Error : " . mysqli_connect_error());
            }
        
            $sql = "SELECT Time_ID FROM APPOINTMENTS
                WHERE Status_ID = 1 and Date = ?";
                
            $availableTimes = [];
        
            if(!($stmt = mysqli_prepare($connection, $sql))) {
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }
            
            mysqli_stmt_bind_param($stmt, 's', $date);
        
            if(!(mysqli_stmt_execute($stmt))) {
                mysqli_stmt_close($stmt);
                throw new Exception("Preparing Statement failed: " . mysqli_error($connection));
            }
            
            $result = mysqli_stmt_get_result($stmt);
        
            while($row = mysqli_fetch_assoc($result)) {
                $availableTimes[] = $row['Time_ID'];
            }
        
            mysqli_stmt_close($stmt);
            
            return $availableTimes;

            } catch(Exception $e) {
                echo "#016 - Something went wrong. Please try again later."; 
                return null;
            }
    }
?>