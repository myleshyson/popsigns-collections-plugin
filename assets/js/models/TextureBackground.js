import {
  Model
} from '@vuex-orm/core';

class TextureBackground extends Model {
  static entity = 'textureBackgrounds';

  static fields() {
    return {
      id: this.increment(),
      collection_id: this.attr(null),
      title: this.string(''),
      path: this.string(''),
      media_id: this.attr(null),
      is_active: this.boolean(false),
    };
  }
}

export default TextureBackground;