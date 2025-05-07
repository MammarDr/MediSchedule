<?php

session_start();

require_once('../../src/core/functions.php');
require_once('../../src/core/auth.php');

if(!isset($_SESSION['user_id']) || !isset($_COOKIE['user_session'])) {
    removeSession();
    header("location: index.php");
    exit();
} else {
    isValidSession($_SESSION['user_id'] , $_COOKIE['user_session']);
    if(!isset($_SESSION['doctor_id']) && !isDoctor($_SESSION['user_id'])) {
       header("location: index.php");
       exit();
    }
}

$appointments = getAppointmentsByDoctorID($_SESSION['doctor_id']);

$active = [];
$past = [];
$cancelled = [];
$today = [];
if($appointments) {
    foreach ($appointments as $appointment) {
        switch ($appointment['Status_ID']) {
            case 1:
                $datetimeString = $appointment['Date'] . ' ' . $appointment['Time'] ;
                if(strtotime($datetimeString) > time()) {
                    $active[] = $appointment;

                    $date = new DateTime($appointment['Date']);
                    $now = new DateTime();
                    if ($date->format('Y-m-d') === $now->format('Y-m-d')) {
                        $today[] = $appointment;
                    }

                    break;
                } 
                changeAppointementStatus($appointment["ID"], 3);
                $appointment['Status_ID'] = 3;
                $cancelled[] = $appointment;
                break;
            case 2:
                $past[] = $appointment;
                break;
            case 3:
                $cancelled[] = $appointment;
                break;
        }
    }
}

?>


<script src="../assests/js/utility.js"></script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="../assests/Css/Components.css?v=<?= time(); ?>">
</head>
<body>
    <style>
        section.doctor .stats {
            justify-content: space-between;
            padding: 0;
            flex-wrap: nowrap;
        }

        section.doctor .stats .card {
            width: 33%;
            align-items: center;
            gap: 0.5rem;
            padding-top: 1.5rem;
        }

        section.doctor .stats h1 {
            color:var(--primary-color);
        }

        section.doctor #appointments {
            padding:0;
            margin-top: 2rem;
        }

        section.doctor #appointments .card-default {
            width: 100%;
        }

    </style>
    <header>
        <div class="container flex">
            <a href="doctor.php" class="logo">
                <span class="primary">Medi</span>
                <span class="secondary">Dashboard</span>
            </a>
            <div class="menu flex">
                <div class="menu-options flex">    
                        <div class="text-card">
                            <span>Hi</span>
                            <form action="#" method="POST" class="hide"> <input type="submit" name="logout" value="Logout">  </form>
                        </div>
                </div>
            </div>
        </div>
    </header>

    <section class="doctor managing">
        <div class="container">

        <div class="header">
                <div>
                    <h1>Doctor Dashboard</h1>
                    <span>manage your appointments.</span>
                </div>
        </div>

            <div class="cards stats">
                <div class="card">
                    <span>TOTAL SCHEDULED</span>
                    <h1><?php echo count($active); ?></h1>
                </div>
                <div class="card">
                    <span>TODAY'S APPOINTMENTS</span>
                    <h1><?php echo count($today); ?></h1>
                </div>
                <div class="card">
                    <span>CANCELLED</span>
                    <h1><?php echo count($cancelled); ?></h1>
                </div>
            </div>

            <div class="cards" id="appointments">
                <div class="card card-default"> <span>No Appointments Found</span>  </div>
            </div>
        </div>
    </section>

    <footer>    
    <div class="container">
        <div class="wrapper flex">
            <div class="about-us flex">
                <a href="doctor.php" class="logo">
                    <span class="primary">Medi</span>
                    <span class="secondary">Dashboard</span>
                </a>
                <span>
                At the core of MediDashboard lies a commitment to enhancing clinical workflows—giving doctors back the gift of time.
                Our intelligent platform transforms appointment management into a seamless extension of patient care, with data-driven insights and automated tools
                </span>
            </div>
            <div class="our-links flex">
                <h2>Links</h2>
                <div class="box">
                    <a href="index.php">Visit Home</a>
                    <a href="contact.php">Message Us</a>
                </div>
            </div>
            <div class="our-contact flex">
                <h2>Contact</h2>
                <ul class="box">
                    <li>Email: admin@medidashboard.com</li>
                    <li>Phone: 055123344</li>
                    <li>Address: 123 street Chlef, Algeria.</li>
                </ul>
            </div>
        </div>
    </div>    
    <div class="copyright">
        <span>&#169; <?php echo  date("Y"); ?> MediSchedule. All rights preserved.</span>
    </div>
</footer>
    
    <script src="../assests/js/ui/dropdown.js"></script>
    <script>
        const parent = document.querySelector('#appointments');

        function createDefaultDiv(isActive) {
            const div = document.createElement('div');
            div.classList.add('card', 'card-default');
            div.innerHTML = ` <span>No Appointments Found</span>  `;
            return div;
        }

        const createDiv = (id, time, name, gender, age, date, reason) => {
            const div = document.createElement('div');
            div.classList.add("card", "card-reserve");
            div.dataset.id = id;

            div.innerHTML = `
                <div class="line active"></div>
                        <div class="top">
                            <div class="details">
                                <div class="status active">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4">
                                    </path></svg>
                                    active
                                </div>
                                <div class="time">
                                    ${time}
                                </div>
                            </div>
                            <div class="name">
                                ${name} <span>${gender} ${age}</span>
                            </div>
                            <span class="date">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                                ${date}
                            </span>
                        </div>
                        <div class="bottom">
                            <div class="primary">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                                </span>
                                <div>
                                    <span>
                                        Visit Reason :
                                    </span>
                                    <div class="reason">
                                        ${reason}
                                    </div>
                                </div>
                            </div>
                            <div class="secondary">     
                                <button class="remove" onclick="removeAppoint(event)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                                    Remove
                                </button>
                                <button class="confirm" onclick="confirmAppoint(event)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4"></path></svg>
                                    Confirm
                                </button>

                            </div>
                            
                        </div>
            
            `;

            return div;
        }

        const toIgnore = new Map;

        function getFilteredLength(appointments) {
            return appointments.reduce((count, appoint) => {
                if (!toIgnore.has(String(appoint.ID))) {
                    count++;
                }
                return count;
            }, 0);
        }

        const setAppointments = (bypass = true) => {
            const appointments = <?php echo json_encode($active) ?>;

            if(getFilteredLength(appointments) === 0) {
                const defaultDiv = createDefaultDiv();
                parent.innerHTML = '';
                parent.appendChild(defaultDiv);
                return;
            } 

            if(bypass == false) return;

            if(document.querySelector('.card-default')) document.querySelector('.card-default').remove();

            appointments.forEach(app => {
                if(toIgnore.has(String(app['ID']))) return;
                const child = createDiv(app["ID"], app["Time"], app["Name"],  app["Gender"],  app["Age"], app["Date"], app["Visit_Reason"]);
                parent.appendChild(child);
            });
            
        }

        setAppointments();

        function isIdExist(id) {
            const appointments = <?php echo json_encode($active); ?>;
            return appointments.some(appoint => appoint['id'] === id);
        }

        function removeAppoint(e) {
            const target = e.currentTarget.closest('.card');
            if(isIdExist(target.dataset.id)) return;
            removeAppointmentByID(target.dataset.id);
            toIgnore.set(target.dataset.id, undefined);
            target.remove();
            setAppointments(false);
        }

        function confirmAppoint(e) {
            const target = e.currentTarget.closest('.card');
            if(isIdExist(target.dataset.id)) return;
            confirmAppointmentByID(target.dataset.id);
            toIgnore.set(target.dataset.id, undefined);
            target.remove();
            setAppointments(false);
        }

    </script>
</body>
</html>


