<template>
  <div>
   <Select placeholder="Select Collection" :value="currentCollection"></Select> 
  </div>
</template>

<script>
import 'element-ui/lib/theme-chalk/index.css';
import { Select } from 'element-ui'
export default {
  name: 'Fields',
  components: {
    Select
  },
  data() {
    return {
      currentCollection: ''
    }
  },
  computed: {
    collections() {
      return this.$store.getters['entities/collections/all']()
    }
  }
}
</script>

<style>

</style>
