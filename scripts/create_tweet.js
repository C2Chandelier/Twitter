let btnTweet = document.getElementById("btn-tweet");
let inputTweet = document.getElementById("input_tweet");

let btnReply = document.getElementById("btn-reply");
let inputReply = document.getElementById("input_reply");

function disableBtn(btn, input)
{
    input.addEventListener("input", function(){
        this.value.length == 0 ? btn.setAttribute("disabled", true) : btn.removeAttribute("disabled");
    })
}

disableBtn(btnTweet, inputTweet);
disableBtn(btnReply, inputReply);
