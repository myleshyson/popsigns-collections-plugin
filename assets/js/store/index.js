import Vue from 'vue';
import Vuex, {
  MutationTree
} from 'vuex';

import VuexORM from '@vuex-orm/core';

import Collection from '../models/Collection';
import CollectionItem from '../models/CollectionItem';
import Color from '../models/Color';
import Hashtag from '../models/Hashtag';
import LightBackground from '../models/LightBackground';
import DarkBackground from '../models/DarkBackground';
import TextureBackground from '../models/TextureBackground';
import TextObject from '../models/TextObject';

Vue.use(Vuex);

const database = new VuexORM.Database();

database.register(Collection, {});
database.register(CollectionItem, {
  state: {
    currentItemId: null
  },
  mutations: {
    setCurrentItemId(state, id) {
      state.currentItemId = id;
    }
  },
});
database.register(Color, {});
database.register(Hashtag, {});
database.register(LightBackground, {});
database.register(DarkBackground, {});
database.register(TextObject, {});
database.register(TextureBackground, {});

export default new Vuex.Store({
  plugins: [VuexORM.install(database)],
});