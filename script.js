window.onload = () => {
    backgroundColor();
}

let bg_check = 0;
let bg_bool_check = true;
let bg_color = 255;

function backgroundColor() {
    setTimeout(function () {
        let max = 30;
        let bg = document.getElementById('header_background');
        bg_check += 1;
        if (bg_bool_check === true) {
            bg_color -= 2;
            if (bg_check >= max){
                bg_bool_check = false;
                bg_check = 0;
            }
        }
        if (bg_bool_check === false){
            bg_color += 2;
            if (bg_check >= max){
                bg_bool_check = true;
                bg_check = 0;
            }
        }
        bg.style.background = `rgba(255,${bg_color},${bg_color},0.9)`;

        backgroundColor();
    }, 150);
}

function checkPassword() {
    let password = document.getElementById('password');
    let password_repeat = document.getElementById('password_repeat');

    let pass_error_repeat = document.getElementById('password_err_repeat');
    let pass_error = document.getElementById('password_err');

    let error = false;

    if (password.value != password_repeat.value && password_repeat.value.length != 0) {
        pass_error_repeat.classList.remove('reg_password_err_true');
        pass_error_repeat.innerText = 'Пароли не совпадают!';
        password_repeat.classList.add('input_err');
        error = true;
    } else if (password_repeat.value.length == 0){
        pass_error_repeat.innerText = '';
        password_repeat.classList.remove('input_err');
        error = true;
    } else {
        pass_error_repeat.classList.add('reg_password_err_true');
        pass_error_repeat.innerText = 'Пароли совпадают!';
        password_repeat.classList.remove('input_err');
    }

    if (password.value.length < 9 && password.value.length != 0){
        pass_error_repeat.classList.remove('reg_password_err_true');
        pass_error.innerText = 'Пароль слишком короткий!';
        password.classList.add('input_err');
        error = true;
    } else {
        pass_error.innerText = '';
        password.classList.remove('input_err');
    }

    if (!error && password_repeat.value.length != 0) checkStrongPassword(password, pass_error);

}

function checkStrongPassword(pass, pass_err) {
    console.log(123)
    let number = pass.value.match(/[0-9]/);
    let big_letter = pass.value.match(/[A-Z]/);
    let special = pass.value.match(/[^\w]/);

    let big = ( big_letter != null ) ? true : false;
    let num = ( number != null ) ? true : false;
    let spe = ( special != null ) ? true : false;

    let error;

    if( big == true && num == true && spe == true ){
        error = false;
        pass_err.innerText = "Ваш пароль сильный!";
    }else if ( (big == true && num == true) || (num == true && spe == true) || (big == true && spe == true ) ) {
        error = false;
        pass_err.innerText = "Ваш пароль средний!";
    }else if ( big == true || num == true || spe == true ) {
        error = true;
        pass_err.innerText = "Ваш пароль слабый!";
    }else {
        error = true;
        pass_err.innerText = "Ваш пароль очень слабый!";
    }

    let sumbit = document.querySelector('#sumbit_reg');
    console.log(sumbit)

    if (error) {
        pass_err.classList.remove('reg_password_err_true');
        sumbit.disabled = true;
    } else {
        pass_err.classList.add('reg_password_err_true');
        sumbit.disabled = false;
    }
}