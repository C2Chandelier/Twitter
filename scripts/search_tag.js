let div = document.getElementById("result");

let tagARR = [];

function getTag() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/getTag.php", true);
  xhr.send();
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = JSON.parse(xhr.responseText);

      for (compt = 0; compt < response.length; compt++) {
        tagARR.push(response[compt]);
      }
    }
  };
}

window.onload = getTag();

let input = document.getElementById("search_tag");

function autocomplete(input, tagARR) {
  let currentFocus;
  input.addEventListener("input", function (e) {
    let a,
      b,
      i,
      valeur = this.value;
    let arrayVal = valeur.split(" ");
    let val = arrayVal.pop();
    closeAllLists();
    if (!val) {
      return false;
    }
    currentFocus = -1;
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    this.parentNode.appendChild(a);
    for (i = 0; i < tagARR.length; i++) {
      if (tagARR[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        b = document.createElement("DIV");
        b.innerHTML =
          "<strong>" + tagARR[i].substr(0, val.length) + "</strong>";
        b.innerHTML += tagARR[i].substr(val.length);
        b.innerHTML += "<input type='hidden' value='" + tagARR[i] + "'>";
        b.addEventListener("click", function (e) {
          let inputarray = input.value.split(" ");
          if (inputarray.length > 1) {
            inputarray.pop();
            inputarray.push(this.getElementsByTagName("input")[0].value);
            input.value = inputarray.join(" ");
            closeAllLists();
          } else {
            input.value = this.getElementsByTagName("input")[0].value;
            closeAllLists();
          }
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

input.onclick = autocomplete(input, tagARR);

document.getElementById("search_button").addEventListener("click", (e) => {
  e.preventDefault();
  search_tag();
});

function search_tag(reload = null) {
  let tags = document.getElementById("search_tag").value;
  let data = new FormData();
  data.append("tags", tags);
  let req = new XMLHttpRequest();
  req.open("POST", "../php/searchTag.php", true);
  req.send(data);
  req.onreadystatechange = () => {
    if (req.readyState == 4 && req.status == 200) {
      let obj = JSON.parse(req.responseText);
      console.log(obj);
      create_tweet(obj, reload);
    }
  };
}

function returnDate(objKey) {
  let timestamp = new Date(objKey);
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

function create_tweet(obj, reload) {
  let taille = obj.data.length;
  document.getElementById("div_container").innerHTML = "";
  document.getElementById("div_reply").innerHTML = "";
  let tweet = document
    .getElementById("div_container")
    .getElementsByTagName("div");
  let reply = document.getElementById("div_reply").getElementsByTagName("div");

  for (let i = 0; i < taille; i++) {
    let div = document.createElement("div");
    document.getElementById("div_container").appendChild(div);
    div.classList.add("col-12");
    div.classList.add("tweet");
    div.id = obj.data[i][0].id_tweet;
    if (obj.data[i][0].id_tweet_reply != null) {
      div.classList.add(obj.data[i][0].id_tweet_reply);
      div.innerHTML += "En réponse à " + obj.data[i][0].handle_reply + ":";
      div.innerHTML += "<hr>";
    }
    div.innerHTML =
            `<div id="` +
            obj.data[i][0].id_tweet +
            `" class="d-flex border-bottom border-secondary py-3 ps-2 tweet" onclick="redirect(this)"> <a href="#">  <img class="pfp" style='content: url("./medias/profile_pictures/` +
            obj.data[i][0].avatar +
            `');"> </a> <div class="px-2 align-middle flex-grow-1"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username">` +
            obj.data[i][0].name +
            `</p> <div class="d-flex"> <p class="mb-0 px-1 handle">` +
            obj.data[i][0].handle +
            `</p> <p class="mb-0 px-1 published_date">` +
            returnDate(obj.data[i][0].date) +
            `</p> </div> <button href="#" class="hover-btn"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16"> <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" /> </svg> </button> </div><p class="text-light mb-1">` +
            obj.data[i][0].content +
            `</p>
                    <span class="text-light fw-light me-4 retweet"> 
                        <button id="RT" class="hover-btn" onclick="retweet(this.id)"> 
                            <i class="bi bi-recycle text-light"></i>
                            ` +
              obj.data[i][0].retweet +
          ` 
                        </button> 
                    </span>
                    <span class="text-light fw-light mx-4 likes"> 
                        <button id="like" class="hover-btn" onclick="like(this.id)"> 
                            <i class="bi bi-suit-heart text-light"></i>
                            ` +
              obj.data[i][0].likes +
          `
                        </button> 
                    </span>
                    <span class="text-light fw-light mx-4 reply"> 
                        <button id="answer" class="hover-btn" onclick="answer(this.id)"> 
                            <i class="bi bi-chat text-light"></i>
                            ` +
              obj.data[i][0].reply +
          `
                        </button> 
                    </span> 
                </div>
                </div>
                `;

    Array.from(document.getElementsByClassName("content")).forEach(
      (element) => {
        element.addEventListener("click", (e) => {
          e.stopImmediatePropagation();
          console.log(e.target);
          let target = e.target.parentNode;
          for (let j = 0; j < tweet.length; j++) {
            if (target.id != tweet[j].id) {
              tweet[j].style.display = "none";
            }
            for (let k = 0; k < reply.length; k++) {
              reply[k].style.display = "none";
            }
          }
          for (let i = 0; i < obj.replies.length; i++) {
            if (obj.replies[i][0].id_tweet_reply == target.id) {
              let div = document.createElement("div");
              document.getElementById("div_reply").appendChild(div);
              div.classList.add("col-12");
              div.classList.add("tweet");
              div.id = obj.replies[i][0].id_tweet;
              div.innerHTML += obj.replies[i][0].name;
              div.innerHTML += obj.replies[i][0].handle;
              div.innerHTML += "<hr>";
              div.innerHTML += obj.replies[i][0].content;
              div.innerHTML += "<hr>";
              div.innerHTML += obj.replies[i][0].date;
              div.innerHTML += obj.replies[i][0].likes;
              div.innerHTML += " likes ";
              div.innerHTML += obj.replies[i][0].retweet;
              div.innerHTML += " retweet ";
              div.innerHTML += "<hr>";
              div.innerHTML +=
                '<button class="reply" id="RT' +
                obj.replies[i][0].id_tweet +
                '" data-target="' +
                obj.replies[i][0].id_tweet_reply +
                '" onclick="retweet(this.id)">RT</button>';
              div.innerHTML +=
                '<button class="reply" id="like' +
                obj.replies[i][0].id_tweet +
                '" data-target="' +
                obj.replies[i][0].id_tweet_reply +
                '" onclick="like(this.id)">like</button>';
              div.style.display = "block";
            }
          }
        });
      }
    );
  }
  if (reload != null) {
    reload.click();
  }
}

document.getElementById("retour").addEventListener("click", () => {
  search_tag();
});

function retweet(id) {
  strippedId = id.replace("RT", "");
  let formdata = new FormData();
  formdata.append("id_tweet", strippedId);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./retweet.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.responseText;
      console.log(response);
      if (response == "success") {
        if (document.getElementById(id).classList.contains("reply")) {
          search_tag(
            document
              .getElementById(document.getElementById(id).dataset.target)
              .getElementsByClassName("content")[0]
          );
        } else {
          search_tag();
        }
      }
    }
  };
}

function like(id) {
  strippedId = id.replace("like", "");
  let formdata = new FormData();
  formdata.append("id_tweet", strippedId);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./like.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      if (response == "success") {
        if (document.getElementById(id).classList.contains("reply")) {
          search_tag(
            document
              .getElementById(document.getElementById(id).dataset.target)
              .getElementsByClassName("content")[0]
          );
        } else {
          search_tag();
        }
      }
    }
  };
}

function answer(id) {
  let string = id.replace("answer", "");
  let id_tweet = string.split("/")[0];
  let handle = "@" + string.split("/")[1];

  let parent = document.getElementById(id).parentNode;
  console.log(parent);
  parent.innerHTML += "<hr>";
  parent.innerHTML +=
    '<label for="area' + id_tweet + '">En réponse à ' + handle + "</label>";
  parent.innerHTML +=
    '<input type="text" id="area" class="form-control"' +
    id_tweet +
    ' placeholder="Entrez votre réponse">';
  parent.innerHTML +=
    '<button id="button" class="btn btn-primary mt-2"' +
    id_tweet +
    "/" +
    handle +
    ' onclick="sendAswer(this.id)">Envoyer</button>';
}

function sendAswer(id) {
  let string = id.replace("button", "");
  let id_tweet = string.split("/")[0];
  let content = document.getElementById("area" + id_tweet).value;
  console.log(content);
  console.log(id_tweet);
  let formdata = new FormData();
  formdata.append("content", content);
  formdata.append("id_tweet", id_tweet);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./answer.php", true);
  xhr.send(formdata);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let response = xhr.responseText;
      //console.log(response);
      if (!document.getElementById(id).classList.contains("reply")) {
        search_tag();
      }
    }
  };
}
