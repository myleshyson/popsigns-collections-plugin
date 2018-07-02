<template>
  <div class="bg-white p-8">
    <FormInput 
      type="text" 
      :value="collection.title" 
      @input="updateCollection('title', $event)" />
    <CollectionColors :collection="collection" />
    <CollectionTextures :collection="collection" />
    <div class="block mt-8">
      <Button type="primary" @click="addCollectionItem">Add Collection Design</Button>
    </div>
    <div 
      class="block mt-8" 
      v-if="collectionItems && collectionItems.length > 0"
    >
    <FormSelect 
      :value="currentItem"
      @change="selectCollectionItem"
      placeholder="Dude">
      <SelectOption 
        v-for="item in collectionItems"
        :key="item.id"
        :label="item.title"
        :value="item">
      </SelectOption>
    </FormSelect>
    </div>
    <div 
      class="block mt-8"
      v-if="currentItem">
        <Row>
          <Column :span="12">
            <FormInput :value="currentItem.title" @input="updateCollectionItem('title', $event)" />
          </Column>
          <Column :span="12">
            <InputNumber :value="currentItem.price_increase" @change="updateCollectionItem('price_increase', $event)" />
          </Column>
        </Row>
        <div class="flex items-center justify-between mt-8 w-full">
          <label >Has Lettering Option</label>
          <RadioSwitch
          :value="currentItem.has_lettering_option"
          @change="updateCollectionItem('has_lettering_option', $event)"
          ></RadioSwitch>
        </div>
        <div class="flex items-center justify-between mt-8 w-full">
          <label>Has Custom Text?</label>
          <RadioSwitch
          :value="currentItem.has_custom_text"
          @change="updateCollectionItem('has_custom_text', $event)"
          ></RadioSwitch>
        </div>
        <div class="flex items-center justify-between mt-8 w-full">
          <label>Has Background Image</label>
          <RadioSwitch
          :value="currentItem.has_backgrounds"
          @change="updateCollectionItem('has_backgrounds', $event)"
          ></RadioSwitch>
        </div>
        <div class="mt-8">
          <Backgrounds :collectionItem="currentItem" />
        </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import Component from "vue-class-component"
import Collection from '../models/Collection'
import CollectionItem from '../models/CollectionItem'
import Color from '../models/Color'
import CollectionColors from './CollectionColors.vue'
import CollectionTextures from './CollectionTextures.vue'
import Backgrounds from './Backgrounds.vue'
import { 
  Button, 
  Select, 
  Option, 
  InputNumber, 
  Input,
  Switch
} from 'element-ui'

@Component({
  name: 'CollectionFields',
  props: {
   collection: {
      type: Object,
      required: true
    } 
  },
  components: {
    Button,
    'FormSelect': Select,
    'SelectOption': Option,
    InputNumber,
    'FormInput': Input,
    CollectionColors,
    CollectionTextures,
    'RadioSwitch': Switch,
    Backgrounds
  }
})

export default class CollectionFormFields extends Vue {
  currentItemId = 0 

  get collectionItems() {
    return CollectionItem.query().where('collection_id', this.collection.id).get() 
  }

  get currentItem() {
    return CollectionItem.getters('find')(this.currentItemId)
  }

  addCollectionItem() {
    Collection.dispatch('update', {
      where: this.collection.id,
      insert: ['CollectionItems'],
      data: {
        collection_items: [
          {
            title: 'New Collection Item',
            collection_id: this.collection.id
          }
        ]
      }
    })
  }

  selectCollectionItem(e) {
    this.currentItemId = e.id
    this.$store.commit('entities/CollectionItems/setCurrentItemId', e.id)
  }

  updateCollection(field, value) {
    Collection.dispatch('update', {
      where: this.collection.id,
      data: {
        [field]: value
      }
    })
  }

  updateCollectionItem(field, value) {
    Collection.dispatch('update', {
      where: this.collection.id,
      data: {
        collection_items: [
          {
            id: this.currentItem.id,
            [field]: value,
          }
        ]
      }
    })
  }

  updateColor(id, field, color) {
    Color.dispatch('update', {
      where: id,
      data: {
        [field]: color
      }
    })
  }
}
</script>

<style lang="scss">
</style>
