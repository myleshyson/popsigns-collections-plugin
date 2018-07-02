<template>
  <Row>
    <Column :span="12">
      <div class="bg-grey-lightet p-16">
        <SignCanvas />
      </div>
    </Column>
    <Column :span="12">
      <CollectionFormFields :collection="collection" @collectionItemSelected="setCurrentCollectionItem"></CollectionFormFields>
    </Column>
  </Row>
</template>

<script>
import Vue from 'vue'
import Component from "vue-class-component"
import CollectionItem from '../models/CollectionItem'
import CollectionFormFields from './CollectionFormFields.vue'
import SignCanvas from './SignCanvas.vue'

@Component({
  components: {
    CollectionFormFields,
    SignCanvas
  },
})

export default class CollectionForm extends Vue {

  collection = {}

  beforeRouteEnter(to, from, next) {
    if(to.name === 'create') {
      next(vm => {
        vm.$store.dispatch('entities/collections/insert', 
          {
            data: {
              title: 'New Collection',
              collection_items: []
            }
          }).then(({collections}) => {
          vm.collection = collections[0]
        })
      })
    }
  }

  setCurrentCollectionItem(id) {
    this.currentCollectionItem = CollectionItem.getters('find')(id);
  }
}
</script>

<style>
</style>
