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
    context.drawImage(video, 0,0,640, 480);
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

// $("#snap").click(function ()  {
// 	var canva= canvas.toDataURL('medias/uploads');
// 	if (navigator.geolocation) {
// 	  navigator.geolocation.getCurrentPosition(function (position) {
// 	$.ajax({
// 	   type: "POST",
// 	   url: 'play',
// 	   dataType: 'text',
// 	   data:  {
// 	  image : canva,
// 	  longitude: position.coords.longitude,
// 	  latitude: position.coords.latitude,
// 	  }
	  
// 	});
// 	console.log(data);
//   })
//   } 
//   });	 
  $("#snap").click(function ()  {
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
	  }
	  
	});
  })
