import {
  Model
} from '@vuex-orm/core';
import CollectionItem from './CollectionItem';

class DarkBackground extends Model {
  static entity = 'DarkBackgrounds';

  static fields() {
    return {
      id: this.increment(),
      collection_item_id: this.attr(null),
      title: this.string(''),
      path: this.string(''),
      media_id: this.attr(null),
      is_active: this.boolean(false),
      collection_item: this.belongsTo(CollectionItem, 'collection_item_id'),
    };
  }
}

export default DarkBackground;