<h1>Book Your Appointment</h1>
<span>With our website you can schedule an appointment with click of buttons!</span>
<div class="card">
    <h2>Your personal informations</h2>
    <span>Fill the inputs to start</span>
    <form action="" method="POST">
        <div>
            <label>
                <span>First name</span>
                <div >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user absolute left-3 top-3 h-4 w-4 text-gray-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <input type="text" name="aptFName" placeholder="Jhon" autocomplete="off" required>
                </div>
            </label>
            <label>
                <span>Last name</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user absolute left-3 top-3 h-4 w-4 text-gray-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <input type="text" name="aptLName" placeholder="Doe" autocomplete="off" required>
                </div>
            </label>
        </div>
        <label>
            <span>Phone</span>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone absolute left-3 top-3 h-4 w-4 text-gray-400"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <input type="tel" name="aptTel" placeholder="0561138820" autocomplete="off" required> 
            </div>
        </label>        
        <label>
            <span>Birth Date</span>
            <div >
                <input type="date" name="aptDOB" min="1899-12-31" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
        </label>
        
        <label>
        <span>Gender</span>        
            <select name="aptGender" id="" required>
                <option value="" disabled selected>Select an option</option>
                <option value="M">Male</option>
                <option value="F">Female</option>
            </select> 
        </label>
       
        <input type="submit" name="person" value="Submit">
    </form>
</div>

<script>
  const select = document.querySelector("select");
  const date = document.querySelector("input[type='date']");
  const inputs = document.querySelectorAll('input');


  select.addEventListener("change", (e) => {
    if (e.currentTarget.value) {
        e.currentTarget.classList.add("has-value", "input-focus");
    } else {
        e.currentTarget.classList.remove("has-value", "input-focus");
    }
  });

  if (select.value) {
    select.classList.add("has-value", "input-focus");
  }

  if (date.value) {
    date.classList.add("has-value");
    date.parentElement.classList.add('input-focus');
  }

  inputs.forEach(input => {
    input.addEventListener('change', (e) => {
        if(e.currentTarget.value) {
            e.currentTarget.classList.add("has-value");
            e.currentTarget.parentElement.classList.add('input-focus');
        } else {
            e.currentTarget.classList.remove("has-value");
            e.currentTarget.parentElement.classList.remove('input-focus');
        }
    });
  });
</script>