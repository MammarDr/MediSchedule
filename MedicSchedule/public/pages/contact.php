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
    <title>Contact</title>
    <link rel="stylesheet" href="../assests/Css/Components.css?v=<?= time(); ?>">
</head>
<body>
    <?php require_once('../components/Headers.php'); ?>
    <section class="contact">
        <div class="container">

            <h1>Contact Us</h1>

            <div class="card">
                <h2>How Can We Help ?</h2>
                <span>Specify the reason of contacting us</span>
                <form action="index.php" method="POST">
                    <label>
                        <span>Full name</span>
                        <input type="text" name="guestName" placeholder="Jhon Doe" required>
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" name="guestEmail" placeholder="example@gmail.com" required>
                    </label>
                    <label>
                        <span>Contact reason</span>
                        <textarea name="guestReason" required></textarea>
                    </label>
                    <input type="submit" name="contact" value="Submit">
                </form>

            </div>

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