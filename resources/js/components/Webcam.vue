<template>
  <div class="d-block">
    <transition name="fade" mode="out-in">
      <div class="camera row justify-content-center align-items-center" v-show="state.camera.open">
        <div class="col text-center">
          <a href="#" class="close">
            <i class="far fa-arrow-left text-white"></i>
          </a>
          <video id="video" :width="config.width" :height="config.height" playsinline autoplay></video>
          <div class="row justify-content-center mt-3">
            <a href="#">
              <i class="fas fa-camera text-white fa-3x"></i>
            </a>
          </div>
        </div>
      </div>
    </transition>
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
        }
      },
      config: {
        width: 0,
        height: 0
      }
    }
  },

  methods: {
    openCamera() {
      this.init()
    },
    init() {
      try {
        console.log('webcam.js log: Initiating webcam with asynchronous method')
        navigator.mediaDevices.getUserMedia({
          audio: false,
          video: true
        })
          .then(stream => {
            this.stream = stream
            window.stream = stream
            let { width, height } = this.stream.getTracks()[0].getSettings()
            this.config.width = width
            this.config.height = height

            document.getElementById('video').srcObject = this.stream

            this.state.camera.open = true
          })
      } catch (e) {
        console.log('webcam.js log: Exception on Webcam: ' + e.toString())
      }
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
  position: absolute;
  bottom: 0;
}
.close {
  position: fixed;
  top: 0;
  left: 0;
  padding: 20px;
}

</style>