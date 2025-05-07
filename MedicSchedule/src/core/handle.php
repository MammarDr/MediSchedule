<?php

require_once('functions.php');

    try {

        session_start();

        header('Content-Type: application/json');

        // REQUEST #1
        if(isset($_GET['date'])) {
            if(!isset($_SESSION['user_id'])) {
                http_response_code(401); // Unauthorized (anonymous request)
                echo json_encode(['state' => 'fail', 'message' => 'Unauthorized']);
                return;
            }
            echo json_encode(getInvalidTimeByDate($_GET['date']));
            return;
        }

        // REQUEST #2 & #3
        if((isset($_GET['id']) && isset($_GET['session'])) && (isset($_GET['delete']) || isset($_GET['confirm']))) {

            if(!isset($_SESSION['user_id'])) {
                http_response_code(401); 
                echo json_encode(['state' => 'fail', 'message' => 'Unauthorized']);
                return;
            }

            $userID = $_SESSION['user_id'];
            $cookie = urldecode($_GET['session']);
            $appointmentID = $_GET['id'];

            if(!isValidSession($userID, $cookie)) {
                http_response_code(403); // Forbidden (known but not authorized)
                echo json_encode(['state' => 'fail', 'message' => 'Forbidden']);
                return;
            }

            if (isset($_GET['delete'])) {
                $result = removeAppointmentByID($appointmentID);
                echo json_encode(['state' =>  $result ? 'success' : 'fail']);
                return;
            }
        
            if (isset($_GET['confirm'])) {
                if (!isDoctor($userID)) {
                    http_response_code(403);
                    echo json_encode(['state' => 'fail', 'message' => 'Doctor privileges required']);
                    return;
                }
                $result = confirmAppointmentByID($appointmentID);
                echo json_encode(['state' => $result ? 'success' : 'fail']);
                return;
            }
        }


        http_response_code(400);
        echo json_encode(['state' => 'fail', 'message' => 'Invalid request']);

      } catch(Exception $e) {
        echo "Something went wrong. Please try again later."; 
        http_response_code(500); 
    }
    

?>