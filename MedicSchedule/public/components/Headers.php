<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<header>
    <div class="container flex">
        <a href="index.php" class="logo">
            <span class="primary">Medi</span>
            <span class="secondary">Schedule</span>
        </a>

        <div class="menu flex">
            <div class="menu-options flex">    
                    <a href="index.php" class="<?php echo $current_page == 'index.php' ? 'text-focus' : '' ?>">
                        <span>Home</span>
                    </a>

                    <a href="booking.php" class="<?php echo $current_page == 'booking.php' ? 'text-focus' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"></path><path d="M16 2v4"></path><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg>
                        <span>Booking</span>
                    </a>
      
                    <a href="appointment.php" class="<?php echo $current_page == 'appointment.php' ? 'text-focus' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <span>Appointments</span>
                    </a>
           
                    <a href="contact.php" class="<?php echo $current_page == 'contact.php' ? 'text-focus' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92V19a2 2 0 0 1-2.18 2 18.62 18.62 0 0 1-8-2.69 18.36 18.36 0 0 1-5.69-5.69A18.62 18.62 0 0 1 3 4.18 2 2 0 0 1 5 2h2.08a2 2 0 0 1 2 1.72 11.47 11.47 0 0 0 .57 2.52 2 2 0 0 1-.45 2.11L8.91 9.91a16 16 0 0 0 5.18 5.18l1.56-1.56a2 2 0 0 1 2.11-.45 11.47 11.47 0 0 0 2.52.57 2 2 0 0 1 1.72 2.17z"></path></svg>
                        <span>Contact</span>
                    </a>
            </div>

            <?php    
                if(isset($_SESSION['user_id'])) {
                    echo '<div class="text-card">
                            <span>Hi</span>
                            <form action="#" method="POST" class="hide"> <input type="submit" name="logout" value="Logout">  </form>
                          </div>
                          <script src="../assests/js/ui/dropdown.js"></script>
                          ';
                } else {
                    echo '<button>Join Us</button>';
                }
            ?>
            
        </div>
    </div>
</header>