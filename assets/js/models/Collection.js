import {
  Model
} from '@vuex-orm/core';
import CollectionItem from './CollectionItem';
import Color from './Color';
import TextureBackground from './TextureBackground';

class Collection extends Model {
  static entity = 'collections';

  static fields() {
    return {
      id: this.increment(),
      title: this.string('New Collection'),
      colors: this.hasMany(Color, 'collection_item_id'),
      background_textures: this.hasMany(TextureBackground, 'collection_id'),
      collection_items: this.hasMany(CollectionItem, 'collection_id'),
    };
  }
}

export default Collection;