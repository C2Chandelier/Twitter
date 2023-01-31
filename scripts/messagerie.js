let list_conv = document.getElementById("list");
let conv = document.getElementById("list_mess");

function getList() {
  list_conv.innerHTML = "";
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/list_conv_mess.php", true);
  xhr.send();
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.responseText);
      console.log(response);

      for (compt = 0; compt < response.length; compt++) {
        list_conv.innerHTML +=
          `<li id=\"` +
          response[compt]["handle"].replace("@", "") +
          `\" onclick="getConv(this.id)" class="d-flex py-2" href="#">
        <img class="pfp" style="content: url('../medias/profile_pictures/` +
          response[compt]["avatar"] +
          `');">
        <div class="px-2 align-middle">
            <div class="d-flex flex-column text-secondary">
                <p class="text-light mb-0">` +
          response[compt]["username"] +
          `</p>
                <p class="mb-0">` +
          response[compt]["handle"] +
          `</p>
            </div>
        </div>
    </li><hr>`;
        console.log(response[compt]["last"]);
      }
    }
  };
}

window.onload = getList();

function getConv(id) {
  let formdata = new FormData();
  formdata.append("handle", id);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/full_conv.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.responseText);
      conv.innerHTML = "";
      conv.parentElement.style.display = "block";

      for (compt2 = 0; compt2 < response.length; compt2++) {
        if (response[compt2]["order"] == "left") {
          conv.innerHTML +=
            '<li class="left">' +
            response[compt2]["content"] +
            "<br>" +
            response[compt2]["date"] +
            "</li>";
        }

        if (response[compt2]["order"] == "right") {
          conv.innerHTML +=
            '<li class="right">' +
            response[compt2]["content"] +
            "<br>" +
            response[compt2]["date"] +
            "</li>";
        }
      }
      Array.from(document.getElementsByClassName("left")).forEach(
        (element) => (element.style.textAlign = "left")
      );
      Array.from(document.getElementsByClassName("right")).forEach(
        (element) => (element.style.textAlign = "right")
      );

      conv.innerHTML += `<div class="d-flex"><input type="text" id="conversation" class="form-control" placeholder="Nouveau message"></input>
      <button class="btn btn-primary" id="send_mess">Envoyer</button></div>`;
      conv.scrollTop = conv.scrollHeight;
      document.getElementById("send_mess").addEventListener("click", (e) => {
        e.preventDefault();
        send_message(id);
      });

      function send_message(id) {
        let handle = id;
        let content = document.getElementById("conversation").value;
        if (content == "" || content == null) {
          return;
        }
        let data = new FormData();
        data.append("handle", handle);
        data.append("content", content);
        let req = new XMLHttpRequest();
        req.open("POST", "../php/new_mess.php", true);
        req.send(data);
        req.onreadystatechange = () => {
          if (req.readyState == 4 && req.status == 200) {
            getConv(id);
            getList();
          }
        };
      }
    }
  };
}

let handleARR = [];

function getHandle() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/get_handle.php", true);
  xhr.send();
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.responseText);

      for (compt = 0; compt < response.length; compt++) {
        handleARR.push(response[compt]);
      }
    }
  };
}

window.onload = getHandle();

let input = document.getElementById("search_handle");

function autocomplete(input, handleARR) {
  let currentFocus;
  input.addEventListener("input", function (e) {
    var a,
      b,
      i,
      val = this.value;
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    this.parentNode.appendChild(a);
    for (i = 0; i < handleARR.length; i++) {
      if (
        handleARR[i].substr(0, val.length).toUpperCase() == val.toUpperCase()
      ) {
        b = document.createElement("DIV");
        b.innerHTML =
          "<strong>" + handleARR[i].substr(0, val.length) + "</strong>";
        b.innerHTML += handleARR[i].substr(val.length);
        b.innerHTML += "<input type='hidden' value='" + handleARR[i] + "'>";
        b.addEventListener("click", function (e) {
          input.value = this.getElementsByTagName("input")[0].value;
          closeAllLists();
          new_input = document.createElement("input");
          new_input.setAttribute("id", "message");
          new_input.setAttribute("placeholder", "Nouveau message");
          document
            .getElementById("search_handle")
            .parentNode.appendChild(new_input);
        });
        a.appendChild(b);
      }
    }
  });
  input.addEventListener("keydown", function (e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      currentFocus++;
      addActive(x);
    } else if (e.keyCode == 38) {
      //up
      currentFocus--;
      addActive(x);
    } else if (e.keyCode == 13) {
      e.preventDefault();
      if (currentFocus > -1) {
        if (x) x[currentFocus].click();
      }
    }
  });
  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = x.length - 1;
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != input) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  document.addEventListener("click", function (e) {
    closeAllLists(e.target);
  });
}

input.onclick = autocomplete(input, handleARR);

document.getElementById("new_conv_button").addEventListener("click", (e) => {
  e.preventDefault();
  NewConv();
});

function NewConv() {
  let handle = document.getElementById("search_handle").value;
  let content = document.getElementById("message").value;
  let data = new FormData();
  data.append("handle", handle);
  data.append("content", content);
  let req = new XMLHttpRequest();
  req.open("POST", "../php/new_conv.php", true);
  req.send(data);
  req.onreadystatechange = () => {
    if (req.readyState == 4 && req.status == 200) {
      getList();
      getConv(handle);
      document.getElementById("search_handle").value = "";
      document
        .getElementById("form_div")
        .removeChild(document.getElementById("message"));
    }
  };
}
