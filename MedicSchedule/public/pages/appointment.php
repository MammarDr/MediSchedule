<?php

session_start();

require_once('../../src/core/functions.php');
require_once('../../src/core/auth.php');

if(!isset($_SESSION['user_id']) || !isset($_COOKIE['user_session'])) {
    removeSession();
} else {
    isValidSession($_SESSION['user_id'] , $_COOKIE['user_session']);

    $appointments = getAppointmentsByUserID($_SESSION['user_id']);

    $active = [];
    $past = [];
    $cancelled = [];

    if($appointments) {
        foreach ($appointments as $appointment) {
            switch ($appointment['Status_ID']) {
                case 1:
                    $datetimeString = $appointment['Date'] . ' ' . $appointment['Time'] ;
                    if(strtotime($datetimeString) > time()) {
                        $active[] = $appointment;
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
}
?>

<script src="../assests/js/utility.js"></script>

<style>

    

    
    
    section.appointment .sections {
        display: flex;
        width: 100%;
        font-size: 18px;
        font-family: roboto;
        border-bottom: 1.25px solid #00000017;
        margin-top: 1rem;
    }

    section.appointment .sections .section {
    cursor: pointer;
    padding: 1rem 2rem;
    }

    section.appointment .sections .selected {
        color: var(--primary-color);
        border-bottom: 1.5px solid var(--primary-color);
    }


    </style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet" />
    <title>Appointment</title>
    <link rel="stylesheet" href="../assests/Css/Components.css?v=<?= time(); ?>">
</head>
<body>
    <?php require_once('../components/Headers.php'); ?>
    <section class="appointment managing">
        <div class="container">

            <div class="header">
                <div>
                    <h1>My Appointments</h1>
                    <span>Edit and check your reservation.</span>
                </div>
                <a href="booking.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                Book New Appointment
                </a>
            </div>

            <div class="sections">
                <div class="section" data-section="0">Upcoming</div>
                <div class="section" data-section="1">Past</div>
                <div class="section" data-section="2">Cancelled</div>
            </div>
            <div class="cards">
                <div class="card card-default">
                    <span>No Appointments Found</span>
                    <a href="booking.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                    Book New Appointment
                    </a>
                </div>
            </div>

        </div>
    </section>
    <?php 
        require_once('../components/Footer.php');
        if(!isset($_SESSION['user_id'])) {
            require_once('../Components/Form.php');
        }
     ?>

     <script>

        const sections = document.querySelectorAll(".section");
        const parent = document.querySelector('.cards');


        function indexToStatus(index) {
            switch(index) {
                case 1:
                    return 'active';
                case 2: 
                    return 'past';
                case 3:
                    return 'cancelled';
            }
        }

        function indexToSvg(index) {
            switch(index) {
                case 1:
                    return '<path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4">';
                case 2: 
                    return '<path d="M21.801 10A10 10 0 1 1 17 3.335"></path><path d="m9 11 3 3L22 4">';
                case 3:
                    return '<path d="M18 6 6 18"></path><path d="m6 6 12 12">';
                    
            }
        }

        function indexToAppointment(index) {
            switch(index) {
                case '0':
                    return <?php echo json_encode($active); ?>;
                case '1': 
                    return <?php echo json_encode($past); ?>;
                case '2':
                    return <?php echo json_encode($cancelled); ?>;      
            }
        }

        function createDiv(id, type, time, name, date, reason, svg) {
            const div = document.createElement('div');
            div.classList.add('card', 'card-reserve', ...(type === "cancelled" ? ['opacity-8'] : []));
            div.dataset.id = id;

            div.innerHTML = `
                    <div class="line ${type}"></div>
                    <div class="top">
                        <div class="details">
                            <div class="status ${type}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    ${svg}
                                </path></svg>
                                ${type}
                            </div>
                            <div class="time">
                                ${time}
                            </div>
                        </div>
                        <div class="name">
                            ${name}
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
            ${type === 'active' ? `<div class="secondary">
                                        
                                            <button class="remove" onclick="removeAppoint(event)">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                                                Remove
                                            </button>
                                      
                                    </div>` : ''}
                        
                    </div>
                 `;
            return div;
        }

        function createDefaultDiv(isActive) {
            const div = document.createElement('div');
            div.classList.add('card', 'card-default');
            div.innerHTML = `
                           <span>No Appointments Found</span>
            ${isActive ?  `<a href="booking.php">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
                             Book New Appointment
                            </a>` : ''} `;
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

        function setSection(target, bypass = false) {
            sections.forEach(element => {

                if(element!== target) {
                    element.classList.remove('selected');
                    return;
                } 
                    
                if(element.classList.contains('selected') && !bypass) return;

                element.classList.add('selected');
                parent.innerHTML = '';
                
                const appointments = indexToAppointment(element.dataset.section);
                
                if(getFilteredLength(appointments) === 0) {
                    const defaultDiv = createDefaultDiv(element.dataset.section == 0);
                    const target = document.querySelector('.sections');
                    parent.appendChild(defaultDiv);
                    return;
                } 
                    
                    
                appointments.forEach(appoint => {
                if(toIgnore.has(String(appoint['ID']))) return;
                const newAppoint = createDiv(appoint['ID'],
                                             indexToStatus(appoint['Status_ID']),
                                             appoint['Time'],
                                             appoint['Doctor_Name'],
                                             appoint['Date'],
                                             appoint['Visit_Reason'],
                                             indexToSvg(appoint['Status_ID']));

                parent.appendChild(newAppoint);
                })

            })
        }

        sections.forEach(section => {
            section.addEventListener('click', (e) => {
                setSection(e.currentTarget);
            })
        })

        setSection(sections[0]);

        function removeAppoint(e) {
            const target = e.currentTarget.closest('.card');
            if(isIdExist(target.dataset.id)) return;
            removeAppointmentByID(target.dataset.id, getCookie('user_session') );
            toIgnore.set(target.dataset.id, undefined);
            target.remove();
            setSection(sections[0], true);
        }

        function isIdExist(id) {
            const appointments = <?php echo json_encode($active); ?>;
            return appointments.some(appoint => appoint['id'] === id);
        }
        

     </script>
</body>
</html>