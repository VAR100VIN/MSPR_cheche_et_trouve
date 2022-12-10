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
$("#save").click(function()  {

	
	$.ajax({
		type: 'POST',          //La méthode cible (POST ou GET)
		url : '/home/play', //Script Cible
		dataType: 'text',
		data   : {
			longitude: document.getElementById('longitude').innerHTML,
			latitude: document.getElementById('latitude').innerHTML,
		}
	});
  });	  


let date1 = Date();
console.log(date1);
document.getElementById('p1').innerHTML = date1;