<?php 
require_once __DIR__ . "/../config/database.php";
require_once("functions.php");

if($_SERVER['REQUEST_METHOD'] == "POST") {

    if(isset($_POST['register'])) {
        if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password_1']) && !empty($_POST['password_2'])) {
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $password_1 = filter_input(INPUT_POST, "password_1", FILTER_DEFAULT);
            $password_2 = filter_input(INPUT_POST, "password_2", FILTER_DEFAULT);

            if($password_1 != $password_2) {
                $_POST = [];
                return;
            }
            
            $_POST = [];
            registerUser($username, $email, $password_1);

        }
    } 

    else if(isset($_POST['login'])) {

        if(!empty($_POST['email']) && !empty($_POST['password_1'])) {
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, "password_1", FILTER_DEFAULT);
            $_POST = [];
            loginUser($email, $password);
        }

    }

    else if(isset($_POST['logout'])) {
            removeSession();
            header('Location: index.php');
            exit;
    } 

    else if(isset($_POST['person'])) {
        if(!empty($_POST['aptFName']) || !empty($_POST['aptLName']) || !empty($_POST['aptTel']) || !empty($_POST['aptDOB']) || !empty($_POST['aptGender'])) {
            $firstName = filter_input(INPUT_POST, 'aptFName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lastName = filter_input(INPUT_POST, 'aptLName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $phone = filter_input(INPUT_POST, 'aptTel', FILTER_SANITIZE_NUMBER_INT);
            $gender = filter_input(INPUT_POST, 'aptGender', FILTER_SANITIZE_SPECIAL_CHARS);
            $dob = filter_input(INPUT_POST, 'aptDOB', FILTER_SANITIZE_SPECIAL_CHARS);
            if (DateTime::createFromFormat('Y-m-d', $dob) === false) {
                return;
            }

            createPerson($_SESSION['user_id'], $firstName, $lastName, $dob, $gender, $phone);
        }
    }

    else if(isset($_POST['appointement'])) {
        if(!empty($_POST['guestDate']) && !empty($_POST['guestTime']) && !empty($_POST['guestReason'])) {
            $visitReason = filter_input(INPUT_POST, 'guestReason', FILTER_SANITIZE_SPECIAL_CHARS);
            $time = filter_input(INPUT_POST, 'guestTime', FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_input(INPUT_POST, 'guestDate', FILTER_SANITIZE_SPECIAL_CHARS);
            if (DateTime::createFromFormat('Y-m-d', $date) === false) {
                //return;
            }

            createAppointment(getPatientID($_SESSION['user_id']), $time, $date, $visitReason);
        }
    }

}

    function setUserSession($userID) {

            global $connection;

            deleteCookie($userID);
      
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }

            $token = bin2hex(random_bytes(32)); 

            $session_sql = "INSERT INTO USER_SESSION(Token, User_ID, Expire_Date)
                            VALUES(?,?,?)";

            $expire_date = date('Y-m-d', strtotime('+30 days'));

            if($stmt = mysqli_prepare($connection, $session_sql)) {
                mysqli_stmt_bind_param($stmt, "sis", $token, $userID, $expire_date);

                if(mysqli_stmt_execute($stmt)) {
                    
                    $_SESSION['user_id'] = $userID;
                    setcookie("user_session", $token, time() + 3600 * 24 * 30, "/");

                } else {
                    session_destroy();
                    echo 'Error: ' . mysqli_error($connection);
                }

                mysqli_stmt_close($stmt);
                
            } else {
                session_destroy();
                echo 'Error: ' . mysqli_error($connection);
            }

    }
  
    function registerUser($username, $email, $password) {
                global $connection;
               
                $hash = password_hash($password, PASSWORD_DEFAULT);
    
                $sql = "INSERT INTO USERS(Username, Password, Email, Role_ID)
                        VALUES(?, ?, ?, 1)";
              
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }
                
                if ($stmt = mysqli_prepare($connection, $sql)) { // prevent SQL Injections

                    mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $email);

                    if (mysqli_stmt_execute($stmt)) {

                        $user_id = mysqli_insert_id($connection);

                        setUserSession($user_id);

                      //  $_SESSION['patient_id'] = createPatientID($user_id);
                        createPatientID($user_id);
                        header("Location: index.php"); 
                        exit; 
                    } else {
                        echo "Error: " . mysqli_error($connection);
                    }
                
                    mysqli_stmt_close($stmt);
                    
                } else {
                    echo "Error: " . mysqli_error($connection);
                }

    }

    function loginUser($email, $password) {          
            global $connection;

            $id_query = "SELECT ID FROM USERS WHERE Email = ?";
            $id = null;   
                if($stmt = mysqli_prepare($connection, $id_query)) {

                    mysqli_stmt_bind_param($stmt, "s", $email);

                    if(!mysqli_stmt_execute($stmt)) {
                        echo "Error: " . mysqli_error($connection);
                        mysqli_stmt_close($stmt);
                        return;
                    } 

                    mysqli_stmt_bind_result($stmt, $id);

                    if(!mysqli_stmt_fetch($stmt)) {
                        echo "Invalid Email";
                        mysqli_stmt_close($stmt);
                        return;
                    }

                    mysqli_stmt_close($stmt);

                } else {
                    echo "Error: " . mysqli_error($connection);
                    return;
                }
            
            
                $retriveHash = "SELECT Password FROM USERS WHERE ID = ?";

                if($stmt = mysqli_prepare($connection, $retriveHash)) {

                    mysqli_stmt_bind_param($stmt, "i", $id);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo 'Error: ' . mysqli_error($connection);
                        mysqli_stmt_close($stmt);
                        return;
                    }

                    mysqli_stmt_bind_result($stmt, $retrievedHash);

                    if (!mysqli_stmt_fetch($stmt)) {
                        echo "User not found!";
                        mysqli_stmt_close($stmt);
                        return;
                    }

                    if(!password_verify($password, $retrievedHash)) {
                        echo "Invalid Password, Try Again";
                        mysqli_stmt_close($stmt);
                        return;
                    }


                    mysqli_stmt_close($stmt);
                    
                    setUserSession($id);

                    header("Location: index.php"); 
                    exit;
                } else {
                    echo 'Error : ' . mysqli_error($connection);
                    return;
                }

    }

?>