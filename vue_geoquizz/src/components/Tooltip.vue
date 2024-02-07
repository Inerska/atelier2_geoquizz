<script>
export default {
  props: {
    width : {
      type: String,
      required: true
    },
    desc: {
      type: String,
      required: true
    }
  },
  directives : {
    tooltip: {
      mounted(el, binding) {
        el.setAttribute('tooltip', binding.value)
      }
    }
  },
  computed : {
    cssProps() {
      return {
        '--width': (this.width) + "em",
      }
    }
  }
}
</script>

<template>
  <div :style="cssProps" class="iconDiv" v-tooltip="desc">
    <div class="iconSVG">
      <img src="/icons/tooltip.svg" alt="tooltip icon" />
    </div>
  </div>
</template>
<style lang="scss">
.iconDiv {
  height: 2rem;
  width: 2rem;
  margin-top: 1em;
  margin-bottom: 1em;
  margin-left: 1em;
  padding: 0.3em;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  overflow: hidden;
  cursor: pointer;
  transition: width 300ms ease-in-out 0s, background-color 300ms linear 200ms;
}

.iconDiv:hover,
.iconDiv:focus-visible {
  width: var(--width);
  background-color: rgb(51 65 85);
  transition: width 300ms ease-in-out 0s, background-color 100ms linear 0s;
}

.iconDiv:focus-visible {
  outline: 1px solid rgb(51 65 85);
  outline-offset: 4px;
}

.iconDiv:active {
  opacity: 0.9;
}

.iconDiv::after {
  content: attr(tooltip);
  margin-left: 1em;
  animation: fadeIn 600ms linear forwards;
}

.iconSVG {
  height: 2em;
  aspect-ratio: 1 / 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  50% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}


</style>