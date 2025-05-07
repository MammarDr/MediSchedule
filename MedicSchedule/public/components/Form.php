<?php $current_page = basename(htmlspecialchars($_SERVER['PHP_SELF'])); ?>

<div class="auth-form <?php echo $current_page != 'booking.php' ? 'hide' : '' ?>">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" class="form">
        <p class="title">Register</p>
        <p class="message">Fill the inputs and get full access to our app. </p>
                
        <label>
            <input class="input" type="text" name="username" required="">
            <span>Username</span>
        </label> 
        <label>
            <input class="input" type="email" name="email" required="">
            <span>Email</span>
        </label> 
            
        <label>
            <input class="input" type="password" name="password_1" required minlength="8">
            <span>Password</span>
        </label>
        <label>
            <input class="input" type="password" name="password_2" required minlength="8">
            <span>Confirm password</span>
        </label>
        <input type="submit" class="submit" name="register" value="Submit">
        <div>
            <span class="method">Already have an acount ?</span>
             <a href="#">Signin</a>
        </div>
    </form>
</div>
    
<script src ='../assests/js/ui/Form.js?v=<?= time(); ?>'></script>


