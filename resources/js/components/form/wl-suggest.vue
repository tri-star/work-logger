<template>
  <div>
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
    async loadSuggestions () {
      this.itemList = [
        { id: 1, name: 'Test1' },
        { id: 2, name: 'Test2' },
        { id: 3, name: 'Test3' },
        { id: 4, name: 'Test4' }
      ]
    },
    handleClick (item) {
      this.innerValue = item.id
      this.innerName = item.name
      this.showList = false
    },

    async handleInput (event) {
      this.innerName = event.srcElement.value
      await this.loadSuggestions()
      if (this.itemList.length > 0) {
        this.showList = true
      }
    }
  }
}

</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

.menu {
  position: absolute;
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
  }

  li:hover {
    background-color: lighten($dark-brand-color, 80%);
  }
}

</style>
