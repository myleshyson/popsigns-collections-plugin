<template>
  <!-- <div class="bg-grey-lightest p-8">
    <input type="text" v-model="search">
    <h2 class="mb-8 font-black">Your Collections</h2>
    <ul class="list-reset">
      <li 
        v-for="collection in filteredCollections" 
        :key="collection.id"
        class="p-8 flex i"
      >
        <div class="w-16 h-16 bg-red mr-8"></div>
        <div class="text-2xl font-bold">{{ collection.title }}</div>
        <router-link :to="'/edit/' + collection.id" class="p-4 bg-yellow no-underline">Edit Collection</router-link>
      </li>
    </ul>
  </div> -->
  <div>
    <router-link to="/create">Add New Collection</router-link>
  </div>
</template>

<script>

export default {
  data() {
    return {
      search: ''
    }
  },
  created() {
    if(this.$store.getters)
    for (let i = 0; i < 10; i++) {
      this.$store.dispatch('entities/collections/insert', {
        data: {
          title: `New Title ${i}`
        }
      })
    }
  },
  computed: {
    filteredCollections() {
      let collections = this.$store.getters['entities/collections/all']();
      return collections.filter(collection => {
        let title = collection.title
        return title.toLowerCase().includes(this.search.toLowerCase())
      })
    }
  }
}
</script>

<style>
</style>
