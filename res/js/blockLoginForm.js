const username_input = document.getElementById("username_input");
const password_input = document.getElementById("password_input");
const submit_button = document.getElementById("submit_button");

function checkFormReady() {
    if (username_input.value.length < 4)
        submit_button.disabled = true;
    else if (password_input.value.length < 8)
        submit_button.disabled = true;
    else submit_button.disabled = false;
}

username_input.addEventListener("change", checkFormReady);
password_input.addEventListener("change", checkFormReady);

submit_button.disabled = true;