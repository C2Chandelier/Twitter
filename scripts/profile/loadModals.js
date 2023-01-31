let followersModalButton = document.getElementById("followers");
let followersContent = document.getElementById("followers-content");
let followedModalButton = document.getElementById("followed");
let followedContent = document.getElementById("followed-content");

followersModalButton.addEventListener("click", (e) => {
  followersContent.innerHTML = "";
  loadFollowers();
});

followedModalButton.addEventListener("click", (e) => {
  followedContent.innerHTML = "";
  loadFollowed();
});

function loadFollowers() {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "../php/Profile/loadfollowers.php", true);
  xmlhttp.send();
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200 && xmlhttp.readyState == 4) {
        const result = xmlhttp.responseText;
        const obj = JSON.parse(result);
        console.log(obj);
        for (let key in obj) {
          let user =
            `<div class="d-flex border-bottom border-secondary py-3 px-2 replys"> <a href="#">  <img class="pfp" style="content: url('../medias/profile_pictures/` +
            obj[key].avatar +
            `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
            obj[key].username +
            `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
            obj[key].handle +
            `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
            obj[key].biography +
            `</p>`;
          followersContent.innerHTML += user;
        }
      }
    }
  };
}

function loadFollowed() {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "../php/Profile/loadfollowed.php", true);
  xmlhttp.send();
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200 && xmlhttp.readyState == 4) {
        const result = xmlhttp.responseText;
        const obj = JSON.parse(result);
        console.log(obj);
        for (let key in obj) {
          let user =
            `<div class="d-flex border-bottom border-secondary py-3 px-2 replys"> <a href="#">  <img class="pfp" style="content: url('../medias/profile_pictures/` +
            obj[key].avatar +
            `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
            obj[key].username +
            `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
            obj[key].handle +
            `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
            obj[key].biography +
            `</p>`;
          followedContent.innerHTML += user;
        }
      }
    }
  };
}
