let tweets = document.getElementById("tweets");
let replys = document.getElementById("replys");

function loadTweets() {
  let xmlhttp = new XMLHttpRequest();
  xmlhttp.open("POST", "../php/Profile/loadtweets.php", true);
  let form = new FormData();
  form.append("handle", profileHandle);
  xmlhttp.send(form);
  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200 && xmlhttp.readyState == 4) {
        const result = xmlhttp.responseText;
        const obj = JSON.parse(result);
        console.log(obj);
        createTweet(obj);
      }
    }
  };
}

function createTweet(obj) {
  tweets.innerHTML = "";
  replys.innerHTML = "";
  for (let key in obj.tweets) {
    let tweet;
    if (obj.tweets[key].is_reply == 1) {
      tweet =
        `<div class="d-flex border-bottom border-secondary py-3 px-2 replys"> <a href="#">  <img class="pfp" style="content: url('../medias/profile_pictures/` +
        obj.tweets[key].avatar +
        `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
        obj.tweets[key].username +
        `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
        obj.tweets[key].handle +
        `</p> <p class="mb-0 px-1 published_date">` +
        convertPublishedDate(obj.tweets, key) +
        `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
        obj.tweets[key].content +
        `</p>`;
    } else {
      if (obj.tweets[key]["original_published_date"] != null) {
        tweet =
          `<div class="d-flex flex-column border-bottom border-secondary pb-3"> <div class="d-flex text-dark px-2 py-1 bg-secondary">` +
          obj.username +
          ` a retweeté</div> <div class="d-flex px-2 py-3"> <a href="#"> <img class="pfp" style="content: url('../medias/profile_pictures/` +
          obj.tweets[key].avatar +
          `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
          obj.tweets[key].username +
          `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
          obj.tweets[key].handle +
          `</p> <p class="mb-0 px-1 published_date">` +
          convertPublishedDate(obj.tweets, key) +
          `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
          obj.tweets[key].content +
          `</p><div>`;
      } else {
        tweet =
          `<div class="d-flex border-bottom border-secondary py-3 px-2"> <a href="#">  <img class="pfp" style='content: url("../medias/profile_pictures/` +
          obj.tweets[key].avatar +
          `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
          obj.tweets[key].username +
          `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
          obj.tweets[key].handle +
          `</p> <p class="mb-0 px-1 published_date">` +
          convertPublishedDate(obj.tweets, key) +
          `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
          obj.tweets[key].content +
          `</p>`;
      }
      tweet +=
        '<span class="text-light fw-light me-4 reply">' +
        obj.tweets[key].replies +
        ' <button href="#" id="answer' +
        obj.tweets[key].id +
        "/" +
        obj.tweets[key].handle.replace("@", "") +
        '" onclick="answer(this.id)" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16"> <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z" /> </svg> </button> </span> <span class="text-light fw-light mx-4 retweet">' +
        obj.tweets[key].retweets +
        ' <button href="#" id="RT' +
        obj.tweets[key].id +
        '" onclick ="retweet(this.id)" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16"> <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z" /> </svg> </button> </span> ';
      tweet +=
        '<span class="text-light fw-light mx-4 likes">' +
        obj.tweets[key].likes +
        ' <button href="#" id="like' +
        obj.tweets[key].id +
        '" onclick="like(this.id)" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-suit-heart" viewBox="0 0 16 16"> <path d="m8 6.236-.894-1.789c-.222-.443-.607-1.08-1.152-1.595C5.418 2.345 4.776 2 4 2 2.324 2 1 3.326 1 4.92c0 1.211.554 2.066 1.868 3.37.337.334.721.695 1.146 1.093C5.122 10.423 6.5 11.717 8 13.447c1.5-1.73 2.878-3.024 3.986-4.064.425-.398.81-.76 1.146-1.093C14.446 6.986 15 6.131 15 4.92 15 3.326 13.676 2 12 2c-.777 0-1.418.345-1.954.852-.545.515-.93 1.152-1.152 1.595L8 6.236zm.392 8.292a.513.513 0 0 1-.784 0c-1.601-1.902-3.05-3.262-4.243-4.381C1.3 8.208 0 6.989 0 4.92 0 2.755 1.79 1 4 1c1.6 0 2.719 1.05 3.404 2.008.26.365.458.716.596.992a7.55 7.55 0 0 1 .596-.992C9.281 2.049 10.4 1 12 1c2.21 0 4 1.755 4 3.92 0 2.069-1.3 3.288-3.365 5.227-1.193 1.12-2.642 2.48-4.243 4.38z" /> </svg> </button> </span> <span class="text-light fw-light mx-5"> <button href="#" class="hover-btn"> <svg class="text-light" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16"> <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" /> <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z" /> </svg> </button> </span>';
    }
    tweets.innerHTML += tweet;
  }
  replys.innerHTML = tweets.innerHTML;
}

function formatTimeAgo(date) {
  const formatter = new Intl.RelativeTimeFormat(undefined, {
    numeric: "auto",
  });
  const DIVISIONS = [
    { amount: 60, name: "seconds" },
    { amount: 60, name: "minutes" },
    { amount: 24, name: "hours" },
    { amount: 7, name: "days" },
    { amount: 4.34524, name: "weeks" },
    { amount: 12, name: "months" },
    { amount: Number.POSITIVE_INFINITY, name: "years" },
  ];
  let duration = (date - new Date()) / 1000;

  for (let i = 0; i <= DIVISIONS.length; i++) {
    const division = DIVISIONS[i];
    if (Math.abs(duration) < division.amount) {
      return formatter.format(Math.round(duration), division.name);
    }
    duration /= division.amount;
  }
}

function convertPublishedDate(obj, key) {
  let timestamp = new Date(obj[key].published_date);
  if (obj[key].original_published_date != null) {
    timestamp = new Date(obj[key].original_published_date);
  }
  date =
    timestamp.toLocaleString("fr", { day: "numeric" }) +
    " " +
    timestamp.toLocaleString("fr", { month: "long" });
  let year = date + " " + timestamp.toLocaleString("fr", { year: "numeric" });
  //si le tweet a ete cree il y a moins de 24h, on affiche le temps ecoule et non la date
  if ((new Date().getTime() - timestamp.getTime()) / 1000 >= 3.154e7) {
    return year;
  } else if ((new Date().getTime() - timestamp.getTime()) / 1000 >= 86400) {
    return date;
  } else {
    return formatTimeAgo(timestamp);
  }
}

loadTweets();

function retweet(id) {
  strippedId = id.replace("RT", "");
  let formdata = new FormData();
  formdata.append("id_tweet", strippedId);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/fonctionnalite/retweeter.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      loadTweets();
    }
  };
}

function like(id) {
  strippedId = id.replace("like", "");
  let formdata = new FormData();
  formdata.append("id_tweet", strippedId);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/fonctionnalite/likeTweet.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      loadTweets();
    }
  };
}

function answer(id) {
  let string = id.replace("answer", "");
  let id_tweet = string.split("/")[0];
  let handle = "@" + string.split("/")[1];

  let parent = document.getElementById(id).parentNode;
  parent.innerHTML += "<hr>";
  parent.innerHTML +=
    '<label for="area' + id_tweet + '">En réponse à ' + handle + "</label>";
  parent.innerHTML +=
    '<input type="text" id="area' +
    id_tweet +
    '" placeholder="Entrez votre réponse">';
  parent.innerHTML +=
    '<button id="button' +
    id_tweet +
    "/" +
    handle +
    '" onclick="sendAswer(this.id)">Envoyer</button>';
}

function sendAswer(id) {
  let string = id.replace("button", "");
  let id_tweet = string.split("/")[0];
  let content = document.getElementById("area" + id_tweet).value;
  let formdata = new FormData();
  formdata.append("content", content);
  formdata.append("id_tweet", id_tweet);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/Profile/fonctionnalite/answer.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.responseText;
      //console.log(response);
      loadTweets();
    }
  };
}
