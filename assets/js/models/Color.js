import {
  Model
} from '@vuex-orm/core';

class Color extends Model {
  static entity = 'Colors';

  static fields() {
    return {
      id: this.increment(),
      collection_id: this.attr(null),
      collection_item_id: this.attr(null),
      hex: this.string('#000000'),
      is_dark: this.boolean(false),
      dark_text_color: this.string('#FFFFFF'),
    };
  }
}

export default Color;