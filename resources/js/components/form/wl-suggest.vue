<template>
  <div>
    <div
      :class="{ modal: true, 'modal-visible': showList }"
      @mousedown="handleCloseList"
    />

    <input type="text" :value="innerName" class="text-box" @input="handleInput">

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
    }
  },

  data () {
    return {
      innerValue: 0,
      innerName: '',
      showList: false,
      itemList: []
    }
  },

  mounted () {
    this.innerName = this.text
    this.innerValue = this.value
  },

  methods: {
    handleClick (item) {
      this.innerValue = item.id
      this.innerName = item.name
      this.showList = false
      this.$emit('selected', {
        value: this.innerValue,
        text: this.innerName
      })
    },

    async handleInput (event) {
      this.innerName = event.srcElement.value
      this.itemList = await this.suggestCallback(this.innerName)
      if (this.itemList.length > 0) {
        this.showList = true
      }
    },

    handleCloseList () {
      this.showList = false
    }
  }
}

</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

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
