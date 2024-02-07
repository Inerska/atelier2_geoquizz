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
$color-mid: rgb(51 65 85);

.iconDiv {
  height: 2rem;
  width: 2rem;
  margin-top: 1em;
  margin-bottom: 1em;
  margin-left: 1em;
  padding: .3em;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  overflow: hidden;
  cursor: pointer;
  transition: width 300ms ease-in-out 0s, background-color 300ms linear 200ms;
  &:hover, &:focus-visible {
    width: var(--width);
    background-color: $color-mid;
    transition: width 300ms ease-in-out 0s, background-color 100ms linear 0s;
  }
  &:focus-visible {
    outline: 1px solid $color-mid;
    outline-offset: 4px;
  }
  &:active {
    opacity: 0.9;
  }
  &::after {
    content: attr(tooltip);
    margin-left: 1em;
    animation: fadeIn 600ms linear forwards;
  }
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