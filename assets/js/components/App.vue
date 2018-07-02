<template>
<div>
	hello World
</div>
</template>

<script>
import Vue from 'vue'
import Component from "vue-class-component"
import { Header, Main, Container } from "element-ui";
@Component({
	name: 'App',
	components: {
		Header,
		Main,
		Container,
	}
})

export default class App extends Vue {
	mounted() {
		console.log('Component mounted.')
	}
}
</script>

<style lang="scss">
$--font-path: '/app/plugins/popsigns-collections/node_modules/element-ui/lib/theme-chalk/fonts';
@import '~element-ui/packages/theme-chalk/src/index';
</style>
