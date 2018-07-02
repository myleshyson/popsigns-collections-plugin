<template>
<div class="block mt-8">
  <h2 class="font-black text-xl upper">Colors</h2>
  <Table :data="colors">
    <TableColumn
      width="30"
      prop="id"
      label="">
      <template slot-scope="scope">
        <span 
          class="pointer"
          @click="deleteColor(scope.row.id)">X</span>
      </template>
    </TableColumn>
    <TableColumn
      prop="hex"
      label="Background">
      <template slot-scope="scope">
        <div class="flex items-center">
          <ColorPicker :value="scope.row.hex" @change="updateColor(scope.row.id, 'hex', $event)" />
          <span class="ml-4">{{ scope.row.hex }}</span>
        </div>
      </template>
    </TableColumn>
    <TableColumn
      prop="dark_text_color"
      label="Text Color For Dark Background">
      <template slot-scope="scope">
        <div class="flex items-center">
          <ColorPicker :value="scope.row.dark_text_color" @change="updateColor(scope.row.id, 'dark_text_color', $event)" />
          <span class="ml-4">{{ scope.row.dark_text_color }}</span>
        </div>
      </template>
    </TableColumn>
  </Table>
  <Button @click="addColor">Add Color</Button> 
</div>
</template>

<script>
import Color from '../models/Color'
import {
  ColorPicker,
  Table,
  TableColumn,
  Button
} from 'element-ui'
export default {
 props: {
   collection: {
     type: Object,
     required: true
   }
 },
 components: {
   ColorPicker,
   Table,
   TableColumn,
   Button
 },
 computed: {
    colors() {
      return Color.query().where('collection_id', this.collection.id).get()
    },
 },
 methods: {
   addColor() {
     Color.dispatch('insert', {
       data: {
        collection_id: this.collection.id,
       }
     })
   },
   deleteColor(id) {
     Color.dispatch('delete', id)
   },
  }

}
</script>

<style>

</style>
