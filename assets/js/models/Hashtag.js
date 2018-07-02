import {
  Model
} from '@vuex-orm/core';

class Hashtag extends Model {
  static entity = 'Hashtags';

  static fields() {
    return {
      id: this.increment(),
      collection_item_id: this.attr(null),
      text: this.string('#YOURHASHTAG'),
    };
  }
}

export default Hashtag;