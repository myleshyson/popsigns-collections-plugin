<template>
  <Tabs :value="activeTab.name" @input="updateTab">
    <TabPane label="Dark/Default Image" name="dark_background" entity="DarkBackgrounds">
      <div class="bg-grey-light p-8 text-center">
       <Button @click="openMediaPane">Choose Image</Button>
       {{ collectionItem.title }}
       {{ collectionItem.light_background }}
      </div> 
    </TabPane>
    <TabPane label="Light Image" name="light_background" entity="LightBackgrounds">
      <div class="bg-grey-light p-8 text-center">
       <Button @click="openMediaPane">Choose Image</Button> 
      </div> 
    </TabPane>
  </Tabs>
</template>

<script>
import Vue from "vue";
import Component from "vue-class-component";
import {Tabs, TabPane, Button} from 'element-ui';
import CollectionItem from '../models/CollectionItem'
import Collection from '../models/Collection';

@Component({
  components: {
    Tabs,
    TabPane,
    Button
  },
  name: 'CollectionBackgrounds',
  props: {
    collectionItem: Object 
  }
})

export default class Backgrounds extends Vue {

  frame = false
  activeTab = {
    name: 'dark_background',
    entity: 'DarkBackgrounds'
  } 

  openMediaPane() {
    if ( this.frame ) {
      this.frame.open();
      return;
    }
    this.frame = wp.media({
      title: 'Select or Upload Your Design',
      button: {
        text: 'Use This Media'
      },
      multiple: false
    })
    if(this.frame) {
      this.frame.on('select', () => {
        let image = this.frame.state().get('selection').first().toJSON();
        CollectionItem.dispatch('update', {
          where: this.collectionItem.id,
          insert: [this.activeTab.entity],
          data: {
            [this.activeTab.name]: {
              collection_item_id: this.collectionItem.id,
              media_id: image.id,
              path: image.url,
              title: image.title
            }
          }
        })
      })
    }
    this.frame.open()
  }

  updateTab(e) {
    this.activeTab.name = e
    switch(e) {
      case 'light_background':
        this.activeTab.entity = 'LightBackgrounds'
        break
      case 'dark_background':
        this.activeTab.entity = 'DarkBackgrounds'
        break
      default:
        this.activeTab.entity = 'DarkBackgrounds'    
    } 
  }
}
</script>

<style>
</style>
