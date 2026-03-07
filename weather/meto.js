var xhr=new XMLHttpRequest();
xhr.open("GET","https://api.openweathermap.org/data/2.5/weather?units=metric&q=Oujda&APPID=a1118bd486a9e7a348bd31f131e2c0d5");

xhr.onload=function(){
    
    var data=JSON.parse(xhr.response);
    console.log(data);
    document.querySelector("header>div>span:first-child").textContent = parseInt(data.main.temp) + "°C";
    document.querySelector(".weather span:first-child").textContent = parseInt(data.main.feels_like) + "°C";
    document.querySelector("section>div>div>span").textContent = data.main.humidity + "%";

}
xhr.onerror=function(){
    alert("Error");
}
xhr.onloadstart=function(){
    document.getElementById("loading").style.display="block";
}
xhr.onloadend=function(){
    document.getElementById("loading").style.display="none";
}

xhr.send();

