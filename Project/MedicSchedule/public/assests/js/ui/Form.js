const userPortal = document.querySelector('.auth-form');
const form = document.querySelector('.auth-form form');
const newUser = document.querySelector('header button');
const method = document.querySelector('.auth-form div a');
const labels = document.querySelectorAll('.auth-form label');
const reserveBtn = document.querySelectorAll('.reserve-btn button');

const settings = {
    userName: labels[0],
    email: labels[1],
    password_1: labels[2],
    password_2: labels[3],
    title: document.querySelector('.auth-form .title'),
    description: document.querySelector('.auth-form .method'),
    submit: document.querySelector('.auth-form input[type="submit"]'),
    method: document.querySelector('.auth-form a')
};

function hideForm(event) {
    if(event.target == userPortal) {
        document.querySelector('body').style = 'overflow-y: auto;';
        userPortal.classList.add('hide');
        userPortal.removeEventListener('click', hideForm);
    }
};

function displayForm() {
    document.querySelector('body').style = 'overflow-y: hidden;';
    userPortal.addEventListener('click', hideForm);
    userPortal.classList.toggle('hide');
}

function clearForm() {
    settings['userName'].querySelector('input').value = "";
    settings['email'].querySelector('input').value = "";
    settings['password_1'].querySelector('input').value = "";
    settings['password_2'].querySelector('input').value = "";
    document.querySelectorAll('.auth-form input').forEach(input => {
        const span = input.nextElementSibling;
        span.style.top = '15px';
        span.style.fontSize = '0.9em';
            
    });
}

const setup = (isExist) => {
    clearForm();
    settings['title'].innerHTML = isExist ? 'Login' : 'Register';
    settings['method'].innerHTML = isExist ? 'Signup' : 'Signin';
    settings['description'].innerHTML = isExist ? "Don't have an acount ? " : 'Already have an acount ? ';
    settings['submit'].setAttribute('name', (isExist ? 'login' : 'register'));
    settings['userName'].classList.toggle('hide', isExist);
    settings['userName'].querySelector('input').setAttribute('type', (isExist ? 'hidden' : 'text'));
    settings['password_2'].classList.toggle('hide', isExist);
    settings['password_2'].querySelector('input').setAttribute('type', (isExist ? 'hidden' : 'password'));
}

method.addEventListener('click', () => {
    settings['method'].innerHTML == 'Signin' ? setup(true) : setup(false);
});

newUser.addEventListener('click', () => {
    setup(false);
    displayForm();
});

reserveBtn.forEach(btn => {
    btn.addEventListener('click', () => {
        setup(true);
        displayForm();
    });
})

form.addEventListener('submit', (e) => {
    if(settings['method'].innerHTML == 'Signup') return;
    const x = settings['password_1'].querySelector('input').value
    const y = settings['password_2'].querySelector('input').value
    if(x != y) {
        e.preventDefault();
    }
})

document.querySelectorAll('.auth-form input').forEach(input => {
    input.addEventListener('input', () => {
        const span = input.nextElementSibling;
        if (input.value) {
            span.style.top = '30px';
            span.style.fontSize = '0.7em';
        } else {
            span.style.top = '15px';
            span.style.fontSize = '0.9em';
        }
    });
});