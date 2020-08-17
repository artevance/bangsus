function Webcam() {
   let webcam = {};

   webcam.width = null;
   webcam.height = null;
   webcam.boothEl = null;
   webcam.video = null;
   webcam.stream = null;
   webcam.canvas = null;
   webcam.captured = false;
   webcam.allowed = false;

   webcam.setBoothEl = (b) => {
      console.log('webcam.js log: Booth loaded');
      webcam.boothEl = b;
      console.log(b);
   }
   webcam.start = function (rear = false) {
      webcam.appendVideo();
      if (rear == false) {
         webcam.init();
      } else {
         webcam.initRear();
      }
   }
   webcam.appendVideo = function () {
      console.log('webcam.js log: Appending video element');
      webcam.boothEl.append('<video id="webcamVideo" width="'+webcam.width+'" height="'+webcam.height+'" playsinline autoplay></video>');
      webcam.video = document.getElementById('webcamVideo');
      console.log('webcam.js log: Video appended successfuly');
   };
   webcam.appendCanvas = function () {
      console.log('webcam.js log: Appending canvas element');
      webcam.boothEl.append('<canvas id="webcamCanvas" width="'+webcam.width+'" height="'+webcam.height+'"></video>');
      webcam.canvas = document.getElementById('webcamCanvas');
      console.log('webcam.js log: Canvas appended successfuly')
   }
   webcam.removeVideo = function () {
      console.log('webcam.js log: Removing video element');
      $('video#webcamVideo').remove();
      console.log('webcam.js log: Video removed successfuly');
   }
   webcam.removeCanvas = function () {
      console.log('webcam.js log: Removing canvas element');
      $('canvas#webcamCanvas').remove();
      console.log('webcam.js log: Canvas removed successfuly');
   }
   webcam.init = async function () {
      try {
         console.log('webcam.js log: Initiating webcam with asynchronous method');
         webcam.stream = await navigator.mediaDevices.getUserMedia({
            audio: false,
            video: true
         });
         console.log('webcam.js log: Stream successfuly instantiated');
         webcam.handleSuccess();
      } catch (e) {
         console.log('webcam.js log: Exception on Webcam: ' + e.toString());
         webcam.allowed = false;
      }
   };
   webcam.initRear = async function () {
      try {
         console.log('webcam.js log: Initiating rear camera with asynchronous method');
         webcam.stream = await navigator.mediaDevices.getUserMedia({
            audio: false,
            video: { 'facingMode': "environment" }
         });
         console.log('webcam.js log: Stream successfuly instantiated');
         webcam.handleSuccess();
      } catch (e) {
         console.log('webcam.js log: Exception on Webcam: ' + e.toString());
         webcam.allowed = false;
      }
   };
   webcam.stop = function () {
      webcam.removeVideo();
      webcam.removeCanvas();
      webcam.setCaptured(false);
      webcam.allowed = false;
      webcam.stream.getTracks().forEach((track) => track.stop());
   }
   webcam.handleSuccess = function () {
      webcam.allowed = true;
      window.stream = webcam.stream;
      webcam.video.srcObject = webcam.stream;
      let {width, height} = webcam.stream.getTracks()[0].getSettings();
      webcam.width = width;
      webcam.height = height;
      console.log('webcam.js log: Webcam successfuly started');
   };
   webcam.captureCanvas = function () {
      webcam.appendCanvas();
      console.log('webcam.js log: Trying to capture image');
      if (webcam.allowed == false) {
          webcam.setCaptured(false);
          console.log('webcam.js log: Permission to camera denied');
      }
      webcam.canvas.getContext('2d').drawImage(webcam.video, 0, 0, webcam.width, webcam.height);
      console.log('webcam.js log: Image captured');
      webcam.removeVideo();
      webcam.setCaptured(true);
   };
   webcam.restart = function() {
      webcam.removeCanvas();
      webcam.start();
      webcam.setCaptured(false);
   }
   webcam.serializeCanvas = function () {
      return webcam.canvas.toDataURL('image/jpeg');
   }
   webcam.setCaptured = function (value) {
      webcam.captured = value;
      console.log('webcam.js log: Captured value changed');
   }

   return webcam;
}