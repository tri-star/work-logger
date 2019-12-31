<template>
  <div class="label" @click="$emit('openPopup', value)">
    {{ text }}
  </div>
</template>

<script>

export default {
  props: {
    value: {
      required: true
    },
    text: {
      type: String,
      default: ''
    }
  }

}

</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

.label {
  cursor: pointer;
  border-bottom: 1px solid $dark-brand-color;
  padding: 5px;
  height: 28px;
  transition: background-color 0.3s;
}

.label:hover {
  background-color: lighten($dark-brand-color, 80%);
}

</style>
