$("#signup-name").maxlength({
  threshold: 50,
  placement: "name-input",
  utf8: true,
});

let signUpName = document.getElementById("signup-name");
let signUpEmail = document.getElementById("signup-email");
let signUpDate = document.getElementById("signup-date");
let signUpPassword = document.getElementById("signup-password");
let signUpConfirmPassword = document.getElementById("signup-confirm-password");
let signUpNextButton = document.getElementById("signup-next-button");
let signUpButton = document.getElementById("signup-submit-button");

signUpNextButton.addEventListener("click", (e) => {
  e.preventDefault();
  let isFormValid = checkName() && emailCheckSend() && checkBirthdate();
  if (isFormValid) {
    if (signUpNextButton.dataset.bsToggle !== "modal") {
      signUpNextButton.setAttribute("data-bs-toggle", "modal");
      signUpNextButton.setAttribute("data-bs-target", "#signup-2");
      setTimeout(() => {
        signUpNextButton.click();
      }, 200);
    }
  }
});

signUpButton.addEventListener("click", (e) => {
  e.preventDefault();
  let isFormValid =
    checkName() &&
    emailCheckSend() &&
    checkBirthdate() &&
    checkPassword() &&
    checkConfirmPassword();
  if (isFormValid) {
    signUpSend();
  }
});

function isRequired(value) {
  return value === "" ? false : true;
}

function isBetween(length, min, max) {
  return length < min || length > max ? false : true;
}

function showError(input, message) {
  let parent = input.parentNode;
  input.classList.remove("is-valid");
  input.classList.add("is-invalid");
  parent.querySelector("small").innerText = message;
}

function showSuccess(input) {
  let parent = input.parentNode;
  input.classList.remove("is-invalid");
  input.classList.add("is-valid");
  parent.querySelector("small").innerText = "";
}

function checkName() {
  let valid = false;
  const name = signUpName.value;
  if (!isRequired(name)) {
    showError(signUpName, "Name cannot be empty.");
  } else if (!isBetween(name.length, 1, 50)) {
    showError(signUpName, "Name must be between 1 and 50 letters.");
  } else {
    showSuccess(signUpName);
    valid = true;
  }
  return valid;
}

function checkEmail() {
  let valid = false;
  const email = signUpEmail.value;
  if (!isRequired(email)) {
    showError(signUpEmail, "Email cannot be empty.");
  } else if (!verifyEmail(email)) {
    showError(signUpEmail, "Email is not valid.");
  } else {
    showSuccess(signUpEmail);
    valid = true;
  }
  return valid;
}

function checkBirthdate() {
  let valid = false;
  const birthdate = signUpDate.value;
  if (!isRequired(birthdate)) {
    showError(signUpDate, "Birthdate cannot be empty");
  } else if (!verifyBirthdate()) {
    showError(signUpDate, "You must be at least 13 years old.");
  } else {
    showSuccess(signUpDate);
    valid = true;
  }
  return valid;
}

function checkPassword() {
  let valid = false;
  const password = signUpPassword.value;
  if (!isRequired(password)) {
    showError(signUpPassword, "Password cannot be empty.");
  } else if (!verifyPassword(password)) {
    showError(
      signUpPassword,
      "Password must has at least 8 characters long that include 1 lowercase character, 1 uppercase character and 1 number."
    );
  } else {
    showSuccess(signUpPassword);
    valid = true;
  }
  return valid;
}

function checkConfirmPassword() {
  let valid = false;
  const password = signUpPassword.value;
  const confirmPassword = signUpConfirmPassword.value;
  if (!isRequired(confirmPassword)) {
    showError(signUpConfirmPassword, "Please confirm your password.");
  } else if (!verifyConfirmPassword(password, confirmPassword)) {
    showError(signUpConfirmPassword, "Passwords must match.");
  } else {
    showSuccess(signUpConfirmPassword);
    valid = true;
  }
  return valid;
}

function verifyEmail(email) {
  let emailRegex = new RegExp(
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
  return emailRegex.test(email);
}

function verifyBirthdate() {
  const birthdate = signUpDate.value;
  let birthdateDate = new Date(birthdate);
  let verifDate = new Date();
  verifDate = verifDate.setFullYear(verifDate.getFullYear() - 16);
  return birthdateDate < verifDate;
}

function verifyPassword(password) {
  let passwordRegex = new RegExp(
    /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/
  );
  return passwordRegex.test(password);
}

function verifyConfirmPassword(password, confirmpassword) {
  return password === confirmpassword;
}

function emailCheckSend() {
  if (checkEmail()) {
    let email = new FormData();
    email.append("email", signUpEmail.value);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "./php/index/signup/check.php", true);
    xhr.send(email);
    xhr.onreadystatechange = () => {
      if (xhr.readyState == 4 && xhr.status == 200) {
        let response = xhr.responseText;
        switch (response) {
          case "continue":
            showSuccess(signUpEmail);
            break;
          case "already exists":
            showError(signUpEmail, "This email has already been used.");
            break;
        }
      }
    };
    if (signUpEmail.classList.contains("is-valid")) {
      return true;
    } else {
      return false;
    }
  }
}

function signUpSend() {
  let signUpForm = new FormData();
  signUpForm.append("name", signUpName.value);
  signUpForm.append("email", signUpEmail.value);
  signUpForm.append("birthdate", signUpDate.value);
  signUpForm.append("password", signUpPassword.value);
  signUpForm.append("confirm-password", signUpConfirmPassword.value);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/index/signup/register.php", true);
  xhr.send(signUpForm);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.responseText;
      switch (response) {
        case "success":
          // redirection page utilisateur
          signUpButton.classList.remove("btn-dark");
          signUpButton.classList.add("btn-success");
          signUpButton.innerText = "Success...";
          setTimeout(() => {
            window.location.replace("./user/index.php");
          }, 250);
          break;
        case "fail":
          signUpButton.classList.remove("btn-dark");
          signUpButton.classList.add("btn-danger");
          signUpButton.innerText = "Error";
          break;
      }
    }
  };
}
