<template>
  <div @keydown.esc="handleCancel">
    <div
      :class="{ modal: true, 'modal-visible': showList }"
      @mousedown="handleCancel"
    />

    <template v-if="mode === MODE_EDIT">
      <input
        ref="inputBox"
        type="text"
        :value="innerName"
        :disabled="disabled"
        class="text-box"
        @focus="handleFocus"
        @blur="handleBlur"
        @input="handleInput"
      >
    </template>
    <template v-if="mode === MODE_FIXED">
      <p class="selected-text" @click="handleEdit">
        {{ innerName }}
      </p>
    </template>

    <ul v-if="showList" class="menu">
      <li v-for="item of itemList" :key="item.id" @click="handleClick(item)">
        {{ item.name }}
      </li>
    </ul>
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
    },
    suggestCallback: {
      type: Function,
      required: true
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  data () {
    return {
      mode: 0,
      innerValue: 0,
      innerName: '',
      originalValue: 0,
      originalName: '',
      showList: false,
      itemList: []
    }
  },

  computed: {
    MODE_EDIT: () => 0,
    MODE_FIXED: () => 1
  },

  mounted () {
    this.mode = this.MODE_EDIT
    this.init(this.text, this.value)
  },

  methods: {

    init (text, value) {
      this.innerName = text
      this.innerValue = value
      if (this.innerValue === null || this.innerValue === '') {
        this.innerValue = 0
      }
      this.originalName = text
      this.originalValue = value

      this.mode = this.innerValue === 0 ? this.MODE_EDIT : this.MODE_FIXED
    },

    handleClick (item) {
      this.innerValue = item.id
      this.innerName = item.name
      this.showList = false
      this.mode = this.MODE_FIXED
      this.$emit('selected', {
        value: this.innerValue,
        text: this.innerName
      })
    },

    handleEdit () {
      this.init(this.innerName, this.innerValue)
      this.mode = this.MODE_EDIT
      this.$nextTick(() => {
        this.$refs.inputBox.focus()
      })
    },

    async handleFocus (item) {
      if (this.innerName === '') {
        this.itemList = await this.suggestCallback(this.innerName)
        this.showList = (this.itemList.length > 0)
      }
    },

    handleBlur () {
      if (!this.showList) {
        this.handleCancel()
      }
    },

    async handleInput (event) {
      this.innerName = event.srcElement.value
      this.itemList = await this.suggestCallback(this.innerName)
      this.showList = (this.itemList.length > 0)
    },

    handleCancel () {
      this.showList = false
      if (this.innerName !== '') {
        this.init(this.originalName, this.originalValue)
      } else {
        this.init('', 0)
        this.$emit('selected', {
          value: this.innerValue,
          text: this.innerName
        })
      }
    }
  }
}

</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

input.text-box {
  width: 100%;
}

.selected-text {
  color: rgba($dark-brand-color, 70%);
  @extend .text-box;
}

.menu {
  position: absolute;
  z-index: 100;
  background-color: #fff;
  border: 1px solid rgba(0,0,0,0.5);
  list-style: none;
  margin: 0;
  padding: 0;
  width: 300px;
  transition: opacity 0.5s;

  li {
    display: block;
    background-color: #fff;
    border: none;
    padding: 5px 10px;
    color: rgba(0,0,0,80%);
    transition: background-color 0.3s;
  }

  li:hover {
    background-color: lighten($dark-brand-color, 80%);
  }
}

.modal {
  display: none;
  z-index: 99;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.modal-visible {
  display: block;
}

</style>
