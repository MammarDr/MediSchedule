<?php

session_start();

require_once('../../src/core/functions.php');
require_once('../../src/core/auth.php');

if(!isset($_SESSION['user_id']) || !isset($_COOKIE['user_session'])) {
    removeSession();
} else {
    isValidSession($_SESSION['user_id'] , $_COOKIE['user_session']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../assests/Css/Components.css?v=<?= time(); ?>">
</head>
<body>
    <?php require_once('../components/Headers.php'); ?>
    <section class="booking">
        <div class="container">
            <?php 
                if(isset($_SESSION['user_id'])) {

                    if(!$connection) {
                        echo("Connection failed to the server");
                    }
                    else if(!isPersonExist($_SESSION['user_id'])) {
                        require_once('../components/Person.php'); 
                    } else {
                        require_once('../components/Reserve.php'); 
                    }   
                    
                }
            
            ?>
        </div>
    </section>
    <?php 
        require_once('../components/Footer.php');
        if(!isset($_SESSION['user_id'])) {
            require_once('../Components/Form.php');
        }
     ?>


</body>
</html>