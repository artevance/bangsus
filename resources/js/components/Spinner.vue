<template>
  <div :class="classes" class="mx-1" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script>
export default {
  props: [
    'type',
    'color',
    'size'
  ],
  methods: {
    setClass() {
      let classes = ''

      const type = this.type
      switch (type) {
        case 'grow' :
          classes += 'spinner-grow'
          break;
        case 'border' :
        default:
          classes += 'spinner-border'
      }

      const color = this.color
      const acceptedColor = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark', 'light']
      if (acceptedColor.includes(color))
        classes += ' text-' + color

      const size = this.size
      if (size === 'sm') {
        switch (type) {
          case 'grow' :
            classes += ' spinner-grow-sm'
            break;
          case 'border' :
          default:
            classes += ' spinner-border-sm'
        }
      }

      this.classes = classes
    }
  },

  data() {
    return {
      classes: ''
    }
  },
  created() {
    this.setClass()
  }
}
</script>