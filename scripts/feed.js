let xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
  if (xmlhttp.readyState == XMLHttpRequest.DONE) {
    if (xmlhttp.status == 200) {
      const result = xmlhttp.responseText;
      const obj = JSON.parse(result);
      console.log(obj);
      displayTweet(obj);
      let nbr_tweet = obj.length;
      setInterval(function () {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
          if (xmlhttp.readyState == XMLHttpRequest.DONE) {
            if (xmlhttp.status == 200) {
              const result = xmlhttp.responseText;
              const obj = JSON.parse(result);
              if (nbr_tweet != obj.length) {
                let eval = obj.length - nbr_tweet;
                document.getElementById("countTweet").innerHTML =
                  eval + " nouveau(x) tweet(s) !";
                document.getElementById("countTweet").style.display = "block";
                document
                  .getElementById("countTweet")
                  .addEventListener("click", () => {
                    location.reload();
                  });
              }
            } else if (xmlhttp.status == 400) {
              alert("There was an error 400");
            } else {
              alert("something else other than 200 was returned");
            }
          }
        };
        xmlhttp.open("POST", "../php/class/Feed.php", true);
        xmlhttp.send();
      }, 15 * 1000);
    } else if (xmlhttp.status == 400) {
      alert("There was an error 400");
    } else {
      alert("something else other than 200 was returned");
    }
  }
};

function returnDate(objKey) {
  let timestamp = new Date(objKey["published_date"]);
  if (objKey["original_published_date"] != null) {
    timestamp = new Date(objKey["original_published_date"]);
  }
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

  function formatTimeAgo(date) {
    let duration = (date - new Date()) / 1000;

    for (let i = 0; i <= DIVISIONS.length; i++) {
      const division = DIVISIONS[i];
      if (Math.abs(duration) < division.amount) {
        return formatter.format(Math.round(duration), division.name);
      }
      duration /= division.amount;
    }
  }

  let date =
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

function displayTweet(obj) {
  for (let key in obj) {
    for (let index in obj[key]) {
      if (index == "content") {
        let tweets = document.getElementById("tweets");
        let id_reply = obj[key]["id_tweet_reply"];
        //si c'est null, je retire la valeur de l'id_tweet_reply
        typeof id_reply == "object"
          ? (id_reply = "")
          : (id_reply = "data-target=" + id_reply);

        let timestamp = new Date(obj[key]["published_date"]);
        let options = { dateStyle: "medium", timeStyle: "short" };
        let tweet_date = timestamp.toLocaleString("fr", options);
        let tweet;
        if (obj[key]["original_published_date"] != null) {
          tweet =
            `<div id="` +
            obj[key]["id_tweet"] +
            `" ` +
            id_reply +
            ` class="d-flex flex-column border-bottom border-secondary pb-3 tweet" onclick="redirect(this)"> <div class="d-flex text-dark px-2 py-1 bg-secondary">` +
            obj[key].retweeter_name +
            ` a retweeté</div> <div class="d-flex px-2 py-3"> <a href="#"> <img class="pfp" style="content: url('../medias/profile_pictures/` +
            obj[key].avatar +
            `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
            obj[key].username +
            `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
            obj[key].handle +
            `</p> <p class="mb-0 px-1 published_date">` +
            returnDate(obj[key]) +
            `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
            obj[key].content +
            `</p><div>`;
        } else {
          tweet =
            `<div id="` +
            obj[key]["id_tweet"] +
            `" ` +
            id_reply +
            ` class="d-flex border-bottom border-secondary py-3 ps-2 tweet" onclick="redirect(this)"> <a href="#">  <img class="pfp" style='content: url("../medias/profile_pictures/` +
            obj[key].avatar +
            `');"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
            obj[key].username +
            `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
            obj[key].handle +
            `</p> <p class="mb-0 px-1 published_date">` +
            returnDate(obj[key]) +
            `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
            obj[key].content +
            `</p>`;
        }
        tweet +=
          `<span class="text-light fw-light me-4 reply"> 
                        <button href="#" class="hover-btn"> 
                            <i class="bi bi-chat text-light"></i>
                            ` +
          obj[key]["replys"] +
          `
                        </button> 
                    </span> 
                    <span class="text-light fw-light mx-4 retweet"> 
                        <button href="#" class="hover-btn"> 
                            <i class="bi bi-recycle text-light"></i>
                            ` +
          obj[key]["retweets"] +
          ` 
                        </button> 
                    </span>
                    <span class="text-light fw-light mx-4 likes"> 
                        <button href="#" class="hover-btn"> 
                            <i class="bi bi-suit-heart text-light"></i>
                            ` +
          obj[key]["likes"] +
          `
                        </button> 
                    </span>
                    <p class="fulldate text-secondary mb-0">
                    ` +
          tweet_date +
          `
                    </p>
                    <div class="border-top border-secondary stats py-1 mt-2">
                        <div class="btn text-secondary mb-0 me-2" data-bs-toggle="modal" data-bs-target="#modal_retweet" onclick="toggle(this)">
                            <p class="d-inline text-light mb-0 pe-1">` +
          obj[key]["retweets"] +
          `</p> Retweets
                        </div>
                        <div class="btn text-secondary mb-0" data-bs-toggle="modal" data-bs-target="#modal_likes" onclick="toggle(this)">
                            <p class="d-inline text-light mb-0 pe-1">` +
          obj[key]["likes"] +
          `</p> J'aime
                        </div>
                    </div>
                    <div class="border-top border-secondary stats">
                        <button href="#" class="hover-btn col-3"> 
                            <i class="bi bi-chat text-light"></i>
                        </button> 
                        <button href="#" class="hover-btn col-3"> 
                            <i class="bi bi-recycle text-light"></i>
                        </button> 
                        <button href="#" class="hover-btn col-3"> 
                            <i class="bi bi-suit-heart text-light"></i>
                        </button> 
                    </div>
                </div>
                </div>
                `;
        tweets.innerHTML += tweet;
      }
    }
  }
}

xmlhttp.open("POST", "../php/class/Feed.php", true);
xmlhttp.send();
