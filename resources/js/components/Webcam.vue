<template>
  <div class="d-block">
    <transition name="fade" mode="out-in">
      <div class="webcam-wrapper" v-show="state.open">
        <div class="camera row justify-content-center align-items-center">
          <div class="">
            <video ref="video" :width="config.width" :height="config.height" playsinline autoplay v-show="state.video"></video>
            <canvas ref="canvas" :width="config.width" :height="config.height" v-show="state.canvas"></canvas>
          </div>
          <a href="#" class="close" @click="close">
            <i class="far fa-arrow-left text-white"></i>
          </a>
          <a href="#" class="flip" @click="flip" v-if="!state.captured">
            <i class="far fa-repeat-alt text-white fa-2x"></i>
          </a>
          <div class="capture text-center">
            <a href="#" class="mx-2" v-if="!state.captured" @click="capture">
              <i class="fas fa-camera text-white fa-3x"></i>
            </a>
            <a href="#" class="mx-2" v-if="state.captured" @click="init">
              <i class="fas fa-redo text-white fa-3x"></i>
            </a>
          </div>
        </div>
      </div>
    </transition>
    <input type="hidden" :value="result" @input="$emit('input', $event.target.value)">
    <button class="btn btn-secondary btn-sm" type="button" @click="open" v-if="!state.captured">Ambil Foto</button>
    <button class="btn btn-secondary btn-sm" type="button" @click="open" v-else>Lihat Foto</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: {
        allowCapture: false,
        open: false,
        video: false,
        canvas: false,
        done: false,
        captured: false,
      },
      config: {
        width: 0,
        height: 0
      },
      max: {
        width: 640
      },
      mode: {
        environment: true
      },
      result: ''
    }
  },
  created() {
    this.result = this.value

    if (this.result != '' && this.result != null) {
      this.state.captured = true
      this.state.canvas = true

      let image = new Image()
      image.onload = () => {
        this.config.width = image.width
        this.config.height = image.height
      }
      image.src = this.result
    }

    if (this.link != null) {
      this.captured = true
      this.state.canvas = true
    }
  },

  props: {
    link: {
      default: null,
    },
    value: {
      required: true,
    },
  },
  methods: {
    /**
     *  Open the wrapper
     */
    open() {
      this.state.open = true

      // Handle if the image is already captured and exists as an encoded input
      if (this.state.captured) {
        let image = new Image()
        image.onload = () => this.$refs.canvas.getContext('2d').drawImage(image, 0, 0)
        
        if (this.link != null) {
          this.$axios.get(this.link, { responseType: 'blob' })
            .then(res => {
              (new Promise((resolve, reject) => {
                  let reader = new window.FileReader()
                  reader.onload = () => resolve(reader.result)
                  reader.readAsDataURL(res.data)
                }))
                  .then(img => {
                    this.result = img
                  })
            })
        }

        image.src = this.result
      } else {
        this.init()
      }
    },
    /**
     *  Initialize webcam
     */
    init() {
      this.state.captured = false
      this.state.video = true
      this.state.canvas = false
      try {
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

            this.$refs.video.srcObject = this.stream

            this.state.open = true
          })
      } catch (e) {
        console.log('webcam.js log: Exception on Webcam: ' + e.toString())
      }
    },
    /**
     *  Stop webcam
     */
    stop() {
      this.stream.getTracks().forEach((track) => track.stop());
    },
    /**
     *  Flip the camera orientation
     */
    flip() {
      this.mode.environment = ! this.mode.environment
      this.stop()
      this.init()
    },
    /**
     *  Capture the image
     */
    capture() {
      this.state.captured = true
      this.state.canvas = true
      this.$refs.canvas.getContext('2d').drawImage(this.$refs.video, 0, 0, this.config.width, this.config.height)
      this.stop()
      this.state.video = false

      this.result = this.$refs.canvas.toDataURL('image/jpeg')
      this.$emit('input', this.result)
    },
    /**
     *  Close the component
     */
    close() {
      if (this.state.video) this.stop()
      this.state.open = false
    },
    /**
     *  Reset the component
     */
    reset() {
      this.state = {
        allowCapture: false,
        open: false,
        video: false,
        canvas: false,
        done: false,
        captured: false,
      }
      this.config = {
        width: 0,
        height: 0
      }
      this.max = {
        width: 640
      }
      this.mode = {
        environment: true
      }
      this.result = ''
    }
  }
}
</script>

<style lang="scss" scoped>

.webcam-wrapper {
  position: absolute;
  z-index: 100000000000000000000000000000;
}
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