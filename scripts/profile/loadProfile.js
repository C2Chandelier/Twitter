const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const profileHandle = urlParams.get("handle");

let followButton = document.getElementById("follow-button");
followButton.addEventListener("click", (e) => {
  e.preventDefault();
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "../php/Profile/follow.php", true);
  let form = new FormData();
  form.append("handle", profileHandle);
  xmlhttp.send(form);
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200 && xmlhttp.readyState == 4) {
        const result = xmlhttp.responseText;
        console.log(result);
        location.reload();
      }
    }
  };
});

function loadProfile() {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "../php/Profile/loadprofile.php", true);
  let form = new FormData();
  form.append("handle", profileHandle);
  xmlhttp.send(form);
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200 && xmlhttp.readyState == 4) {
        const result = xmlhttp.responseText;
        const obj = JSON.parse(result);
        console.log(obj);
        if (obj.followedState) {
          document.getElementById("followedstate").style.display = "block";
          document.getElementById("followedstate").innerHTML = "vous suit";
        } else {
          document.getElementById("followedstate").style.display = "none";
          document.getElementById("followedstate").innerHTML = "";
        }
        if (obj.sessionid != obj.userInfo.id) {
          document.getElementById("edit-profile-modal").style.display = "none";
          document.getElementById("follow-button").style.display = "block";
          if (obj.followState) {
            followButton.innerHTML = "Suivi";
            followButton.classList.add("btn-light");
            followButton.classList.remove("btn-primary");
          } else {
            followButton.innerHTML = "Suivre";
            followButton.classList.add("btn-primary");
            followButton.classList.remove("btn-light");
          }
        }
        let timestamp = new Date(obj.userInfo.creation_date);
        //convertit le timestamp en date ex: 22/07/2018 -> Juillet 2018
        let date =
          timestamp.toLocaleString("fr", { month: "long" }) +
          " " +
          timestamp.toLocaleString("fr", { year: "numeric" });
        loadContent("name", obj);
        loadContent("handle", obj);
        loadContent("biography", obj);
        loadContent("localisation", obj);
        loadContent("link", obj);
        document.getElementById("count").innerHTML += obj.tweetsCount;
        document.getElementById("followed").innerHTML += obj.followed;
        document.getElementById("followers").innerHTML += obj.followers;
        document.getElementById("creation_date").innerHTML += date;
        loadPfp(obj.userInfo);
        loadBanner(obj.userInfo);
      }
    }
  };
}

loadProfile();

function loadContent(name, obj) {
  Array.from(document.getElementsByClassName(name)).forEach((element) => {
    if (
      element.tagName.toLowerCase() === "input" ||
      element.tagName.toLowerCase() === "textarea"
    ) {
      if (name == "handle") {
        element.value = obj.userInfo[name].substring(1);
      } else {
        element.value = obj.userInfo[name];
      }
    } else {
      if (obj.userInfo[name] != null) {
        element.innerHTML += obj.userInfo[name];
      } else {
        element.innerHTML = "";
      }
    }
  });
}

function loadPfp(obj) {
  let pfp = document.getElementsByClassName("pfp");
  Array.from(pfp).forEach((element) => {
    element.style.content =
      "url('../medias/profile_pictures/" + obj.avatar + "')";
  });
}

function loadBanner(obj) {
  if (obj.banner == null) {
    Array.from(document.getElementsByClassName("background")).forEach(
      (element) => {
        element.style.background = JSON.parse(obj.theme).color;
      }
    );
    Array.from(document.getElementsByClassName("background-edit")).forEach(
      (element) => {
        element.style.background = JSON.parse(obj.theme).color;
      }
    );
  } else {
    Array.from(document.getElementsByClassName("background")).forEach(
      (element) => {
        element.style.backgroundImage =
          "url('../medias/banners/" + obj.banner + "')";
      }
    );
    Array.from(document.getElementsByClassName("background-edit")).forEach(
      (element) => {
        element.style.backgroundImage =
          "url('../medias/banners/" + obj.banner + "')";
      }
    );
  }
}
