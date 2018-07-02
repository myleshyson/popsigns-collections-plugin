import {
  Model
} from '@vuex-orm/core';
import Color from './Color';
// import Background from './Background'

class TextObject extends Model {
  static entity = 'TextObjects';

  static fields() {
    return {
      id: this.increment(),
      collection_item_id: this.attr(null),
      text: this.string('Custom Text'),
      color: this.hasMany(Color, 'text_object_id'),
      // backgrounds: this.hasOne(Background, 'text_object_id'),
      line_height: this.number(1),
      position: this.number(0),
      font_size: this.number(20),
      font_weight: this.string(700),
      font_family: this.string('BebasKai'),
      border_width: this.number(0),
      border_color: this.hasMany(Color, 'text_object_id'),
    };
  }
}

export default TextObject;