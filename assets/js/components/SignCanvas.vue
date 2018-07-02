<template>
  <div>

  </div>
</template>

<script>
import Vue from 'vue'
import Component from 'vue-class-component'
import CollectionItem from '../models/CollectionItem'
import LightBackground from '../models/LightBackground'

@Component
export default class SignCanvas {
  
  get currentItem() {
    return CollectionItem.getters('find')(this.$store.state.entities.CollectionItems.currentItemId)
  }
}
</script>

<style>
</style>
