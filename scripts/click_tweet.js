let tweets = document.getElementById("tweets");
let makeTweet = document.getElementById("make_tweet");
let tweetSelect = document.getElementById("tweet-select");
let tweetsList = document.getElementById("tweets-list");
let heading = document.getElementById("heading");
let tweetsChild = tweets.children;
let inputHidden = document.getElementById("tweet_reply");
let replys = document.getElementById("replys");
let divSelected = document.getElementById("selected");
let backBtn = document.getElementById("back")

tweetSelect.style.display = "none";

function redirect(tweet)
{
    if (tweetsList.lastElementChild != tweet)
    {
        tweetSelect.style.display = "block";

        let previousTweet = "make_tweet";
        tweet.previousElementSibling != null ? previousTweet = tweet.previousElementSibling.id : previousTweet;
        backBtn.href = "#"+previousTweet;

        inputHidden.value = tweet.id;
        replys.innerHTML = "";
        tweets.style.display = "none";
        makeTweet.style.display = "none";
        heading.innerHTML = "Tweet";

        let tweetSelected = tweet.cloneNode(true);
        tweetsList.innerHTML == "" ? tweetsList.prepend(tweetSelected) : tweetsList.append(tweetSelected);
 
    
        //je cree une div des reponses du tweet selectionne
        for (let child of tweetsChild)
        {
            if (child.dataset.target == tweet.id)
            {
                let answer = child.cloneNode(true);
                replys.append(answer);
            }
        }
    } 

}

function goBack()
{
    if (tweets.style.display == "none")
    {
        if (!tweetSelect.children[0].dataset.target)
        {
            replys.innerHTML = "";
            tweetsList.innerHTML = "";
            tweets.style.display = "block"
            tweetSelect.style.display = "none"
            makeTweet.style.display = "flex";
            heading.innerHTML = "Accueil";
        }
    }
}

