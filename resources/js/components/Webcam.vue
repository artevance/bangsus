<template>
  <div class="d-block">
    <transition name="fade" mode="out-in">
      <div class="camera row justify-content-center align-items-center" v-show="state.camera.open">
        <div class="">
          <video id="video" :width="config.width" :height="config.height" playsinline autoplay v-show="state.video"></video>
          <canvas id="canvas" :width="config.width" :height="config.height" v-show="state.canvas"></canvas>
        </div>
        <a href="#" class="close" @click="close">
          <i class="far fa-arrow-left text-white"></i>
        </a>
        <a href="#" class="flip" @click="flip">
          <i class="far fa-repeat-alt text-white fa-2x"></i>
        </a>
        <div class="capture text-center">
          <a href="#" class="mx-2" v-if="!state.captured" @click="capture">
            <i class="fas fa-camera text-white fa-3x"></i>
          </a>
          <a href="#" class="mx-2" v-if="state.captured" @click="init">
            <i class="fas fa-times text-white fa-3x"></i>
          </a>
          <a href="#" class="mx-2" v-if="state.captured" @click="close">
            <i class="fas fa-check text-white fa-3x"></i>
          </a>
        </div>
      </div>
    </transition>
    <input type="hidden" v-model="result">
    <button class="btn btn-secondary btn-sm" type="button" @click="openCamera">Ambil Foto</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: {
        camera: {
          open: false
        },
        video: false,
        canvas: false
      },
      config: {
        width: 0,
        height: 0
      },
      max: {
        width: 640
      },
      mode: {
        environment: false
      },
      result: ''
    }
  },

  methods: {
    openCamera() {
      this.init()
    },
    init() {
      this.state.captured = false
      this.state.video = true
      this.state.canvas = false
      try {
        console.log('webcam.js log: Initiating webcam with asynchronous method')
        navigator.mediaDevices.getUserMedia({
          audio: false,
          video: { 'facingMode': this.mode.environment ? 'environment' : 'user' }
        })
          .then(stream => {
            this.stream = stream
            window.stream = stream
            let { width, height } = this.stream.getTracks()[0].getSettings()
            if (width > this.max.width) {
              height = (height / width) * this.max.width
              width = this.max.width
            }
            this.config.width = width
            this.config.height = height

            document.getElementById('video').srcObject = this.stream

            this.state.camera.open = true
          })
      } catch (e) {
        console.log('webcam.js log: Exception on Webcam: ' + e.toString())
      }
    },
    stop() {
      this.stream.getTracks().forEach((track) => track.stop());
    },
    flip() {
      this.mode.environment = ! this.mode.environment
      this.stop()
      this.init()
    },
    close() {
      this.state.camera.open = false
    },
    capture() {
      this.state.captured = true
      this.state.canvas = true
      document.getElementById('canvas').getContext('2d').drawImage(document.getElementById('video'), 0, 0, this.config.width, this.config.height)
      this.stop()
      this.state.video = false
    }
  }
}
</script>

<style lang="scss" scoped>

.camera {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: black;
}
.capture {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  padding-bottom: 20px;
}
.close {
  position: fixed;
  top: 0;
  left: 0;
  padding: 20px;
}
.flip {
  position: fixed;
  top: 0;
  right: 0;
  padding: 20px;
}
video {
  margin: 0px;
  padding: 0px;
}

</style>