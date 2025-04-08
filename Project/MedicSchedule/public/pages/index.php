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
    <title>MediSchedule</title>
    <link rel="stylesheet" href="../assests/Css/Components.css?v=<?= time(); ?>">
</head>
<body>
    <?php require_once("../Components/Headers.php"); ?>
    <main>
        <div class="container">
            <div class="primary">
                <div class="message">
                    <span class="dot"></span>
                    <span>New Digital Service For Our Patients</span>
                </div>
                <div class="details gap">
                    <h1>Reserve Now With
                         <span class="gradient-text">Medi Schedule</span>
                    </h1>
                    <span> 
                    Easily schedule, manage, and track your medical appointments with efficiency. Our system ensures seamless 
                    coordination between patients and healthcare providers
                    </span>
                </div>
                <div class="reserve-btn flex">
                    <button>Book Your Appointment</button>
                    <button class="manage">Manage Your Appointment</button>
                </div>
                <div class="advantage flex">
                    <span>The advantage of our platform:</span>
                    <div class="flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                            <span>Simple Fast Reservation</span>
                        </div>
                       
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>Less Time Consuming</span>
                        </div>

                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big h-5 w-5 text-medical-600"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                            <span>Tracking Your Appointments</span>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="secondary">
                <div class="box">
                    <img src="../assests/svg/doctor-illustration.svg" alt="doctor-illustration">
                </div>
            </div>
        </div>
    </main>
    <section class="perks">
        <div class="container">
            <h1>
                <span class="gradient-text">Simple & Fast</span>
                Solution
            </h1>
            <span>
                Our appointment management platform offers you a smooth and intuitive experience for all your medical needs.
            </span>

            <div class="cards">
                
                <div class="card">
                    <div class="text-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                    </div>
                    <h2>24/7 Availability</h2>
                    <span>
                        Access our platform anytime to book an appointment, even outside office hours.
                    </span>
                    <a href="#" class="redirect">Learn More  
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1 h-4 w-4 transition-transform group-hover:translate-x-1"><path d="m9 18 6-6-6-6"></path></svg>
                    </a>
                </div>
        
                
                <div class="card">
                    <div class="text-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <h2>Time-Saving</h2>
                    <span>
                        No more waiting on the phone. Book, modify, or cancel your appointments in just a few clicks.
                    </span>
                    <a href="#" class="redirect">Learn more  
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1 h-4 w-4 transition-transform group-hover:translate-x-1"><path d="m9 18 6-6-6-6"></path></svg>
                    </a>
                </div>
        
                
                <div class="card">
                    <div class="text-card">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield h-6 w-6"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
                    </div>
                    <h2>Data Security</h2>
                    <span>
                        Your personal and medical information is protected by a security system that complies with current standards.
                    </span>
                    <a href="#" class="redirect">Learn more  
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right ml-1 h-4 w-4 transition-transform group-hover:translate-x-1"><path d="m9 18 6-6-6-6"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <section class="tutorial">
        <div class="container">
            <div class="card">
                <div class="tag">
                    <h2>How it works</h2>
                </div>
                <div class="steps">
                    <div class="step">
                        <div class="text-card">
                            <h1>1</h1>
                        </div>
                        <h2>
                            Book Online
                        </h2>
                        <span>
                            Select an available time slot that works for your schedule.
                        </span>
                    </div>
                    <div class="step">
                        <div class="text-card">
                            <h1>2</h1>
                        </div>
                        <h2>
                            Get Confirmed
                        </h2>
                        <span>
                            Receive instant confirmation and reminders before your appointment.
                        </span>
                    </div>
                    <div class="step">
                        <div class="text-card">
                            <h1>3</h1>
                        </div>
                        <h2>
                            Visit Doctor
                        </h2>
                        <span>
                            Arrive for your appointment or reschedule if needed.
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="review">
        <div class="container">
            <h1>Our patients experience</h1>
            <span>Discover the testimonials from our patients and healthcare professionals who use our service.</span>
            <div class="cards">
                
                <div class="card">
                    <span class="stars">★★★★★</span>
                    <span class="quto"><i>"The interface is intuitive and allows me to track my consultations. I highly recommend this service."</i></span>
                    <div class="user">
                        <div class="text-card">
                            <span>B</span>
                        </div>
                        <div class="info">
                            <span>Bilal G.</span>
                            <span>Patient</span>
                        </div>
                    </div>
                </div>
       
                <div class="card">
                    <span class="stars">★★★★★</span>
                    <span class="quto"><i>"Thanks to this service, I can easily manage my appointments without having to call the clinic. It's really convenient!"</i></span>
                    <div class="user">
                        <div class="text-card">
                            <span>A</span>
                        </div>
                        <div class="info">
                            <span>Amine S.</span>
                            <span>Patient</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <span class="stars">★★★★★</span>
                    <span class="quto"><i>"A huge time saver for me and my patients. This tool is essential for my practice."</i></span>
                    <div class="user">
                        <div class="text-card">
                            <span>M</span>
                        </div>
                        <div class="info">
                            <span>Maamar Dr.</span>
                            <span>Doctor</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <section class="end">
        <div class="container">
            <h1>Ready to book your appointment ?</h1>
            <span>Stop wasting time on the phone. Join our platform and manage your medical appointments in just a few clicks.</span>
            <div class="reserve-btn flex">
                <button>Book Your Appointment</button>
                <button class="manage">Manage Your Appointment</button>
            </div>
        </div>
    </section>
    <?php 
        require_once("../Components/Footer.php"); 
        if(!isset($_SESSION['user_id'])) {
            require_once('../Components/Form.php');
        }
    ?>
</body>
</html>