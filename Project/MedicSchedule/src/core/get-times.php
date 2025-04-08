<?php

require_once('auth.php');


try {

    header('Content-Type: application/json');

    global $connection;

    if(!$connection) {
        die("Connection Error : " . mysqli_connect_error());
    }

    $sql = "SELECT Time_ID FROM APPOINTMENTS
        WHERE Status_ID = 1 and Date = ?";
        
    $date = $_GET['date'] ?? null;
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

    } catch(Exception $e) {
        echo "Something went wrong. Please try again later."; 
        http_response_code(500); 
    }
    
    
    echo json_encode($availableTimes);
    

?>