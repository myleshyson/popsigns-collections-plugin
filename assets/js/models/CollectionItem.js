import {
  Model
} from '@vuex-orm/core';
import Collection from './Collection';
// import Color from './Color';
// import TextObject from './TextObject'
// import Hashtag from './Hashtag';
import DarkBackground from './DarkBackground';
import LightBackground from './LightBackground';

class CollectionItem extends Model {
  static entity = 'CollectionItems';

  static fields() {
    return {
      id: this.increment(),
      collection_id: this.attr(null),
      collection: this.belongsTo(Collection, 'collection_id'),
      title: this.string('New Collection Item'),
      price_increase: this.number(0),
      size: this.string('normal'),
      has_lettering_option: this.boolean(false),
      has_custom_text: this.boolean(false),
      has_backgrounds: this.boolean(true),
      lettering_ption: this.string('block'),
      shape: this.string('square'),
      light_background: this.hasOne(LightBackground, 'collection_item_id'),
      dark_background: this.hasOne(DarkBackground, 'collection_item_id'),
      // selected_background: this.hasOne(SelectedImage, 'collection_item_id'),
      // selected_color: this.hasOne(Color, 'collection_item_id'),
      // text_objects: this.hasMany(TextObject, 'collection_item_id'),
      // hashtag: this.hasOne(Hashtag, 'collection_item_id'),
      // logo: this.hasOne(Logo, 'collection_item_id'),
      // approved: this.boolean(false)
    };
  }
}

export default CollectionItem;