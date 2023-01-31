function showStats(modalBody, filePHP)
{
    let formdata = new FormData();
    let id = document.getElementById("tweet_reply").value;
    formdata.append("id", id);
    let xhr = new XMLHttpRequest();
    let liked = modalBody;
    xhr.open("POST", filePHP, true);
    xhr.send(formdata);
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200){
            let response = xhr.responseText;
            let obj = JSON.parse(response);
            console.log(obj.data.length);
            liked.innerHTML = "";
            for(let i = 0; i < obj.data.length; i++){
                liked.innerHTML += `<a class="d-flex" href="#">
                <img class="pfp" style="content: url('./medias/profile_pictures/` +
                obj.data[i].avatar +
                `');">
                <div class="px-2 align-middle">
                    <div class="d-flex flex-column text-secondary">
                        <p class="text-light mb-0">`+obj.data[i].username+`</p>
                        <p class="mb-0">`+obj.data[i].handle+`</p>
                        <p class="text-light mb-0">`+obj.data[i].biography+`</p>
                    </div>
                </div>
            </a>`;
            }
            if(liked.innerHTML == ""){
                liked.innerHTML = "No one liked this";
            }
        }
    }
}

function toggle(element)
{
    if (element.dataset.bsTarget == "#modal_retweet")
    {
        console.log(element);
        showStats(document.getElementById("retweeted"), "./php/modals/modal_retweet.php")
    }
    else if (element.dataset.bsTarget == "#modal_likes")
    {
        showStats(document.getElementById("liked"), "./php/modals/modal_likes.php")
    }
}

// document.getElementById("likes").addEventListener("click", (e) =>{
//     let formdata = new FormData();
//     let id = document.getElementById("tweet_reply").value;
//     formdata.append("id", id);
//     let xhr = new XMLHttpRequest();
//     let liked = document.getElementById("liked");
//     xhr.open("POST", "modal_likes.php", true);
//     xhr.send(formdata);
//     xhr.onreadystatechange = () => {
//         if (xhr.readyState == 4 && xhr.status == 200){
//             let response = xhr.responseText;
//             let obj = JSON.parse(response);
//             console.log(obj.data.length);
//             liked.innerHTML = "";
//             for(let i = 0; i < obj.data.length; i++){
//                 liked.innerHTML += obj.data[i].username;
//                 liked.innerHTML += "<br>" + obj.data[i].handle;
//                 liked.innerHTML += "<br>" + obj.data[i].biography;
//                 liked.innerHTML += "<br><hr>";
//             }
//             if(liked.innerHTML == ""){
//                 liked.innerHTML = "No one liked this";
//             }
//         }
//     }
// })



