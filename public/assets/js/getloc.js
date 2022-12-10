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
function erreur( error ) {
	switch( error.code ) {
		case error.PERMISSION_DENIED:
			console.log( 'L\'utilisateur a refusé la demande' );
			break;     
		case error.POSITION_UNAVAILABLE:
			console.log( 'Position indéterminée' );
			break;
		case error.TIMEOUT:
			console.log( 'Réponse trop lente' );
			break;
	}
	// Function alternative
	alternative();
};
// function callback( position ) {
//     var lat = position.coords.latitude;
//     var lng = position.coords.longitude;
//     console.log( lat, lng );
//     // Do stuff
// }
$("#save").click(function save()  {

	if (navigator.geolocation) {
	  navigator.geolocation.getCurrentPosition(function (position) {
	$.ajax({
	   type: "POST",
	   url: 'play',
	   dataType: 'text',
	   data:  {
	  image : canvas.toDataURL('medias/uploads'),
	  plant:  document.getElementById('plant-id').innerHTML,
	  longitude: position.coords.longitude,
	  latitude: position.coords.latitude,
	  }
	});
  })
  } 
  });	  


let date1 = Date();
console.log(date1);
document.getElementById('p1').innerHTML = date1;