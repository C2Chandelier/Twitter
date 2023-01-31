let svg = document.getElementsByTagName("svg");
let editModal = document.getElementById("edit-profile-modal");
let profilePictureUpload = document.getElementById("profile-picture-upload");
let bannerUpload = document.getElementById("banner-upload");
let editProfile = document.getElementById("edit-profile-button");
let username = document.getElementById("username");
let handle = document.getElementById("handle");
let biography = document.getElementById("biography");
let localisation = document.getElementById("localisation");
let link = document.getElementById("link");

for (let btn of svg) {
  btn.addEventListener("click", function () {
    if (
      btn.dataset.target == "banner-upload" ||
      btn.dataset.target == "profile-picture-upload"
    )
      document.getElementById(btn.dataset.target).click();
  });
}

profilePictureUpload.addEventListener("change", (e) => {
  sendProfilePicture(e);
});

bannerUpload.addEventListener("change", (e) => {
  sendBanner(e);
});

editProfile.addEventListener("click", (e) => {
  sendInfos(e);
});

function sendProfilePicture(e) {
  e.preventDefault();
  let pp = new FormData();
  pp.append("profile-picture", profilePictureUpload.files[0]);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/Edit/profilepicture.php", true);
  xhr.send(pp);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.response;
      console.log(response);
      if (response == "success") {
        location.reload();
        editModal.click();
      }
    }
  };
}

function sendBanner(e) {
  e.preventDefault();
  let banner = new FormData();
  banner.append("banner", bannerUpload.files[0]);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/Edit/banner.php", true);
  xhr.send(banner);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.response;
      console.log(response);
      if (response == "success") {
        location.reload();
        editModal.click();
      }
    }
  };
}

function sendInfos(e) {
  e.preventDefault();
  let formData = new FormData();
  formData.append("username", username.value);
  formData.append("handle", handle.value);
  formData.append("biography", biography.value);
  formData.append("localisation", localisation.value);
  formData.append("link", link.value);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/Edit/info.php", true);
  xhr.send(formData);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.response;
      console.log(response);
      if (response == "success") {
        location.reload();
        editModal.click();
      }
    }
  };
}
