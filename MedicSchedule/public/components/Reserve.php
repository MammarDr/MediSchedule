<h1>Book Your Appointment</h1>
<span>With our website you can schedule an appointment with click of buttons!</span>
<div class="card">
    <h2>Insert Date & Time</h2>
    <span>fill all the below options</span>
    <form action="appointment.php" method="POST">
        <label>
            <span>Full Name</span>
            <div class="input-focus">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user absolute left-3 top-3 h-4 w-4 text-gray-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <input type="tel" name="fullname" value="<?php echo $_SESSION['person_name'] ?>" disabled>
            </div>
        </label>    
        <div>
            <label>
                <span>Date</span>
                <div >
                    <input type="date" name="guestDate" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" required>
                </div>
            </label>
            
            <label>
                <span>Time</span>        
                <select name="guestTime" id="" required>
                    <option value="" disabled selected>Select a time</option>
                    <option value="1"> 08:00</option>
                    <option value="2"> 08:30</option>
                    <option value="3"> 09:00</option>
                    <option value="4"> 09:30</option>
                    <option value="5"> 10:00</option>
                    <option value="6"> 10:30</option>
                    <option value="7"> 11:00</option>
                    <option value="8"> 11:30</option>
                    <option value="9"> 14:00</option>
                    <option value="10">14:30</option>
                    <option value="11">15:00</option>
                    <option value="12">15:30</option>
                    <option value="13">16:00</option>
                </select> 
            </label>
        </div>

        <label>
            <span>Visit Reason</span>
            <textarea name="guestReason" placeholder="Keep it short and simple..." required></textarea>
        </label>
       
        <input type="submit" name="appointement" value="Submit">
    </form>
</div>

<script>
  const form = document.querySelector('form');
  const select = document.querySelector("select");
  const date = document.querySelector("input[type='date']");
  const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];


  // 1 => 8:00 , 2 => 8:30, 3 => 9:00, 4 => 9:30 etc...
  const timeSlotsByDay = new Map([
  ['Sunday', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]],
  ['Monday', [1, 2, 3, 4, 5, 6, 7, 8]],
  ['Tuesday', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]],
  ['Wednesday', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12]],
  ['Thursday', [1, 2, 3, 4, 5, 6, 7, 8]],
  ['Friday', []],
  ['Saturday', []]
]);

const inputs = document.querySelectorAll('input');
const inputAreas = [select, document.querySelector("textarea")];

inputAreas.forEach(input => {
    if(input.value) {
        input.classList.add("has-value", "input-focus");
    } else {
        input.classList.remove("has-value", "input-focus");
    }

    input.addEventListener("change", (e) => {
    if (e.currentTarget.value) {
        e.currentTarget.classList.add("has-value", "input-focus");
    } else {
        e.currentTarget.classList.remove("has-value", "input-focus");
    }
    });

})


inputs.forEach(input => {
    if(input.getAttribute('type')  !== 'submit' && input.value) {
        console.log(input);
        input.classList.add("has-value");
        input.parentElement.classList.add('input-focus');
    }
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

function isHoliday(date) {
    const dayName = new Date(date).toLocaleDateString('en-US', { weekday: 'short' });
    return dayName === 'Sat' || dayName === 'Fri';
}

form.addEventListener('submit', function(e) {
    if(isHoliday(date.value)) e.preventDefault();
});

  date.addEventListener('change', function () {
    const selectedDate = this.value;

    if(isHoliday(selectedDate)) {
        select.value = "";
        select.disabled = true;
        select.classList.remove("has-value", "input-focus");
        select.style.cursor = 'auto';
        form.preventDefault();
    } else {
        select.disabled = false;
        select.style.cursor = 'pointer';

        fetch('../../src/core/handle.php?date=' + selectedDate)
        .then(response => response.json())
        .then(times => {
            for(var i = 0; i < 14; i++) {
                console.log("x");
                if(times.includes(i)) {
                    select.options[i].disabled = true;
                    continue;
                }
                select.options[i].disabled = false;
            }

            select.value = "";
        }).catch(error => {
            console.error('Fetch error:', error); 
        });
    }

    
    });

  
</script>