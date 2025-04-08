const menu = document.querySelector("header .text-card");
const logout = menu.querySelector("form");

function hideDropDown() {
    logout.classList.add('hide');
    document.querySelector('body').removeEventListener('click', hideDropDown);
}

menu.addEventListener("click", (e) => {
    e.stopPropagation();
    logout.classList.remove("hide");
    document.querySelector('body').addEventListener('click', hideDropDown);
});

