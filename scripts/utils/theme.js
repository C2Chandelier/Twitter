let themeElements = document.getElementsByClassName("theme");

getThemeColor();
function getThemeColor() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/theme.php", true);
  xhr.send();
  xhr.onreadystatechange = () => {
    if (xhr.status == 200 && xhr.readyState == 4) {
      let response = xhr.responseText;
      console.log(response);
      Array.from(themeElements).forEach((element) => {
        if (element.tagName.toLowerCase() != "a") {
          element.style.backgroundColor = response;
        } else {
          element.style.color = response;
        }
      });
    }
  };
}
