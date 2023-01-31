let loginNextButton = document.getElementById("login-next-button");
let loginButton = document.getElementById("login-button");
let loginLabel = document.getElementById("login-login-label");
let login = document.getElementById("login-login");
let loginInput = document.getElementById("login-used");
let loginPassword = document.getElementById("login-password");

loginNextButton.addEventListener("click", (e) => {
  e.preventDefault();
  checkLoginUsed();
});

loginButton.addEventListener("click", (e) => {
  e.preventDefault();
  sendLoginForm();
});

function checkLoginUsed() {
  let loginUsed = document.getElementById("login-used").value;
  if (verifyEmail(loginUsed)) {
    loginLabel.innerText = "Email";
    login.style.color = "gray";
    login.value = loginUsed;
  } else {
    loginLabel.innerText = "Username";
    login.style.color = "gray";
    login.value = loginUsed;
  }
}

function verifyEmail(email) {
  let emailRegex = new RegExp(
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  );
  return emailRegex.test(email);
}

function sendLoginForm() {
  let loginForm = new FormData();
  loginForm.append("login", loginInput.value);
  loginForm.append("password", loginPassword.value);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./php/index/login.php", true);
  xhr.send(loginForm);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.response);
      switch (response.state) {
        case "success":
          // redirection page utilisateur
          loginPassword.classList.remove("is-invalid");
          login.classList.add("is-valid");
          loginPassword.classList.add("is-valid");
          loginButton.classList.remove("btn-dark");
          loginButton.classList.add("btn-success");
          loginButton.innerText = "Connecting...";
          setTimeout(() => {
            window.location.replace("./user/index.php");
          }, 250);
          break;
        case "incorrect email":
          login.classList.add("is-invalid");
          loginPassword.classList.remove("is-invalid");
          break;
        case "incorrect username":
          login.classList.add("is-invalid");
          loginPassword.classList.remove("is-invalid");
          break;
        case "incorrect password":
          login.classList.remove("is-invalid");
          login.classList.add("is-valid");
          loginPassword.classList.add("is-invalid");
          break;
      }
    }
  };
}
