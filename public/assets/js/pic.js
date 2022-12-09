let canvas = document.querySelector("#canvas");
let context = canvas.getContext("2d");
let video = document.querySelector("#video");

if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({ video: true}).then((stream) => {
        video.srcObject = stream;
        video.play();
    });
}

document.getElementById('snap').addEventListener('click', ()=>{
    context.drawImage(video, 0,0,640, 480)
})

// $("#save").click(function()  {

//     console.log('mesv')
//     console.log(canvas.toDataURL('medias/upload'))
//     $.ajax({
//        type: "POST",
//        url: 'apiphoto',
//        dataType: 'text',
//        data:  {
//       image : canvas.toDataURL('medias/upload'),
//       user: document.getElementById('user-id').innerHTML,
//       plant: document.getElementById('plant-id').innerHTML,
//       longitude: document.getElementById('longitude').innerHTML,
//       latitude: document.getElementById('latitude').innerHTML,
//       }
//     });
//   });