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

        $sql = "DELETE FROM USER_SESSION WHERE ? = User_ID";

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
            error_log($e->getMessage());
            echo "Something went wrong. Please try again later.";
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

            if(!$row || $row['Token'] != $cookie) {
                deleteCookie($userID);
                removeSession();
                header('Location: index.php');
            }
            
            return true;
        } catch(Exception $e) {
            error_log($e->getMessage());
            echo "Something went wrong. Please try again later.";
            return false;
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
            echo 'Error';
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
            echo 'Error';
            return null;
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

            return $result;

        } catch(Exception $e) {
            echo "Something went wrong. Please try again later."; 
            echo $patientID . ' ' . $time . ' ' . $date . ' ' . $visitReason;
            return false;
        }

    }
 
    function getAppointmentsByID($userID) {
        global $connection;
        $appointments = []; 

        try {

          /*  $sql = "SELECT A.ID , A.Status_ID, CONCAT(P.First_Name, ' ', P.Last_Name) AS Full_Name,
                A.Visit_Reason, A.Date, S.Description
                FROM APPOINTMENTS A JOIN C D
                ON A.Doctor_ID = D.ID JOIN PERSONS P 
                ON D.User_ID = P.User_ID JOIN APT_STATUS S 
                ON A.Status_ID = S.ID JOIN PATIENTS T
                ON A.Patient_ID = T.ID WHERE T.ID = ?";*/

            $sql = "SELECT A.ID, A.Date, A.Time_ID, A.Visit_Reason, A.Status_ID,  CONCAT(P.First_Name, ' ', P.Last_Name) AS Doctor_Name
                    FROM USERS U JOIN PATIENTS T
                    ON U.ID = T.User_ID
                    JOIN APPOINTMENTS A
                    ON T.ID = A.Patient_ID JOIN DOCTORS D
                    ON A.Doctor_ID = D.ID JOIN PERSONS P
                    ON D.User_ID = P.User_ID
                    WHERE U.ID = ?";
                
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
                $appointments[] = $row;
            }
            
            mysqli_stmt_close($stmt);
            return $appointments;
        } catch (Exception $e) {
            error_log($e->getMessage()); 
            echo "Something went wrong. Please try again later."; 
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
            echo "Something went wrong. Please try again later."; 
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
    
/*    function getWorkTime() {
        global $connection;

        if(!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        try {
            $map = [];

            $sql = "SELECT T.ID AS Time_ID, D.ID AS Day_ID FROM WORK_TIME WT JOIN DAYS D ON D.ID = Day_ID
                    JOIN TIME T ON T.ID = WT.Time_ID
                    ORDER BY D.ID ASC, T.Time ASC;";

            $result = mysqli_query($connection, $sql);

            while($row = mysqli_fetch_assoc($result)) {
                $map[$row['Day_ID']][] = $row['Time_ID'];
            }

            return $map;
                
        } catch(Exception $e) {
            echo "Something went wrong. Please try again later."; 
            return null;
        }

    }*/

?>