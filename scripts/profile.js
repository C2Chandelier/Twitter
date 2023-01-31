let xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
        if (xmlhttp.status == 200) {
            const result = xmlhttp.responseText;
            const obj = JSON.parse(result);
            for (let key in obj.data) {

                stylesheet = document.styleSheets[0];
                for (let index in obj.data[key][0]) {
                    if (index == "creation_date") {
                        let timestamp = new Date(obj.data[key][0][index]);
                        //convertit le timestamp en date ex: 22/07/2018 -> Juillet 2018
                        let date = timestamp.toLocaleString("fr", { month: "long" }) + " " + timestamp.toLocaleString("fr", { year: "numeric" });
                        stylesheet.insertRule("." + index + "::after { content:'" + date + "'}", 0);
                    }
                    else if (index == "published_date")
                    {
                        let timestamp = new Date(obj.data[key][0][index]);
                        const formatter = new Intl.RelativeTimeFormat(undefined, {
                            numeric: 'auto'
                          })
                          
                          const DIVISIONS = [
                            { amount: 60, name: 'seconds' },
                            { amount: 60, name: 'minutes' },
                            { amount: 24, name: 'hours' },
                            { amount: 7, name: 'days' },
                            { amount: 4.34524, name: 'weeks' },
                            { amount: 12, name: 'months' },
                            { amount: Number.POSITIVE_INFINITY, name: 'years' }
                          ]
                          
                          function formatTimeAgo(date) {
                            let duration = (date - new Date()) / 1000
                          
                            for (let i = 0; i <= DIVISIONS.length; i++) {
                              const division = DIVISIONS[i];
                              if (Math.abs(duration) < division.amount) {
                                return formatter.format(Math.round(duration), division.name);
                              }
                              duration /= division.amount;
                            }
                          }
                        console.log((new Date().getTime() - timestamp.getTime()) / 1000);
                        let date = timestamp.toLocaleString("fr", { day: "numeric" }) + " " + timestamp.toLocaleString("fr", { month: "long" });
                        let year = date + " " + timestamp.toLocaleString("fr", { year: "numeric" });
                        let published_date = document.getElementsByClassName(index);
                        //si le tweet a ete cree il y a moins de 24h, on affiche le temps ecoule et non la date
                        if ((new Date().getTime() - timestamp.getTime()) / 1000 >= 3.154e+7)
                        {
                            published_date[key].innerHTML = year;
                        }
                        else if ((new Date().getTime() - timestamp.getTime()) / 1000 >= 86400)
                        {
                            published_date[key].innerHTML = date;
                        }
                        else
                        {
                            published_date[key].innerHTML = formatTimeAgo(timestamp);
                        }
                    }
                    else if (index == "link") {
                        let link = document.getElementsByClassName("link");
                        for (let a of link) {

                            let element = obj.data[key][0][index];
                            if (element == null) {
                                a.parentNode.style.display = "none";
                                continue;
                            }
                            if (element.includes("://")) {
                                a.href = "https://" + element.substring(element.indexOf("://") + 3);
                            }
                            else if (typeof element != "undefined") {
                                a.href = "https://" + element;
                            }
                            //permet de réduire le taille du lien
                            element == element.substring(0, 30) ? element : element = element.substring(0, 30) + "...";
                            a.innerHTML = element;
                        }
                    }
                    else if (index == "username")
                    {
                        let username = document.getElementsByClassName("username");
                        username[key].innerHTML += obj.data[key][0]["username"];
                    }
                    else if (index == "handle")
                    {
                        let handle = document.getElementsByClassName("handle")
                        handle[key].innerHTML += obj.data[key][0]["handle"];
                    }
                    else if (index == "likes")
                    {
                        let likes = document.getElementsByClassName("likes");
                        likes[key].innerHTML += obj.data[key][0]["likes"];
                    }
                    else if (index == "reply")
                    {
                        let reply = document.getElementsByClassName("reply")
                        reply[key].innerHTML += obj.data[key][0]["reply"];
                    }
                    else if (index == "retweet")
                    {
                        let retweet = document.getElementsByClassName("retweet");
                        retweet[key].innerHTML += obj.data[key][0]["retweet"];
                    }
                    else if (index == "localisation") {
                        let element = obj.data[key][0][index];
                        if (element != null) {
                            stylesheet.insertRule("." + index + " { display: block !important;", 0);
                            stylesheet.insertRule("." + index + "::after { content:'" + obj.data[key][0][index] + "'}", 0);
                            continue;
                        }
                    }
                    else if (index == "content") {
                        let tweets = document.getElementById("tweets");
                        tweet = tweets.getElementsByClassName("align-middle");
                        console.log(obj.data[key][0]["is_reply"]);
                        if (obj.data[key][0]["is_reply"] == 1)
                        {
                            tweets.innerHTML += '<div class="d-flex border-bottom border-secondary py-3 ps-2 replys"> <a href="#"> <img class="pfp"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username"></p> <div class="d-flex"> <p class="mb-0 px-1 handle"></p> <p class="mb-0 px-1 published_date"></p> </div> <button href="#" class="hover-btn py-0"><i class="bi bi-three-dots text-secondary"></i></button> </div><p class="text-light mb-1">'+obj.data[key][0]["content"]+'</p>';
                        }
                        else
                        {
                            tweets.innerHTML += '<div class="d-flex border-bottom border-secondary py-3 ps-2"> <a href="#"> <img class="pfp"> </a> <div class="px-2 align-middle"> <div class="d-flex text-secondary"> <p class="text-light mb-0 username"></p> <div class="d-flex"> <p class="mb-0 px-1 handle"></p> <p class="mb-0 px-1 published_date"></p> </div> <button href="#" class="hover-btn py-0"><i class="bi bi-three-dots text-secondary"></i> </button> </div><p class="text-light mb-1">'+obj.data[key][0]["content"]+'</p>';
                        }
                        tweet[key].innerHTML += '<span class="text-light fw-light me-4 reply"> <button href="#" class="hover-btn"> <i class="bi bi-chat text-light"></i></button> </span> <span class="text-light fw-light mx-4 retweet"> <button href="#" class="hover-btn"> <i class="bi bi-recycle text-light"></i> </button> </span> ';
                        tweet[key].innerHTML += '<span class="text-light fw-light mx-4 likes"> <button href="#" class="hover-btn"> <i class="bi bi-suit-heart text-light"></i> </button> </span>';
                    }
                    else {
                        console.log(obj.data[key][0][index]);
                        if (obj.data[key][0][index] != null){
                            stylesheet.insertRule("." + index + "::after { content:'" + obj.data[key][0][index] + "'}", 0);
                            let element = document.getElementsByClassName(index);
                            console.log(element[key], index);
                            if (typeof element[key] != "undefined")
                            {
                                if (element[key].tagName.toLowerCase() === "input" || element[key].tagName.toLowerCase() === "textarea" ) element[key].value = obj.data[key][0][index];
                            }
                        }
                    }
                }
            }
            let tweets = document.getElementById("tweets");
            let replys = document.getElementById("replys");
            replys.innerHTML = tweets.innerHTML;
        }
        else if (xmlhttp.status == 400) {
            alert('There was an error 400');
        }
        else {
            alert('something else other than 200 was returned');
        }
    }
};

xmlhttp.open("GET", "php/class/profile.php", true);
xmlhttp.send();