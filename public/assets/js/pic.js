const canvas = document.querySelector("#canvas");
let context = canvas.getContext("2d");
let video = document.querySelector("#video");

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true}).then((stream) => {
        video.srcObject = stream;
        video.play();
    });
}

document.getElementById('snap').addEventListener('click', ()=>{
    context.drawImage(video, 0,0,300, 225);
	context.src = canvas.toDataURL('medias/uploads');
})
// On vérifie que la méthode est implémenté dans le navigateur
if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(function (position) {
		document.getElementById('latitude').innerHTML= position.coords.latitude
		document.getElementById('longitude').innerHTML= position.coords.longitude
		
	})
	} else {
	// Function alternative sinon
	alternative();
}
const plantInfo = document.getElementById('plant-id');
console.log(plantInfo)


// On va récupérer la géolocalisation du user ainsi que l'image qu'il va prendre
  $("#save").click(function ()  {
	var canva= canvas.toDataURL('image/webp', 0.1);
	console.log( document.getElementById('latitude').innerHTML);
	$.ajax({
	   type: "GET",
	   url: 'play',
	   dataType: 'text',
	   data:  {
	  image : canva,
	  longitude: document.getElementById('longitude').innerHTML,
	  latitude: document.getElementById('latitude').innerHTML,
	  plant:  document.getElementById('plant-title').innerHTML,
	  }
	  
	});
  })
  function myFunction() {
	var x = document.getElementById("save");
	var y = document.getElementById("notsave");
	var z = document.getElementById("verif");
	var direct = document.getElementById("video");
	var bouton = document.getElementById("snap");
	var photo = document.getElementById("canvas");
	var after = document.getElementById("after");
	var afterp = document.getElementById("afterp");
	var before = document.getElementById("before");
	var beforep = document.getElementById("beforep");
	if (after.style.display === "none") {
	  after.style.display ="block";
	}
	if(afterp.style.display === "none"){
		afterp.style.display ="block";
	} 
	else {
	  x.style.display = "none";
	  y.style.display ="none";
	  z.style.display ="none";
	  direct.style.display ="none";
	  bouton.style.display ="none";
	  photo.style.display ="none";
	  before.style.display="none";
	  beforep.style.display="none";
	}
  }
