<template>
	<div class="wrapper" :class="{ 'nav-open': $sidebar.showSidebar }">
		<side-bar
			:background-color="sidebarBackground"
			short-title="Argon"
			title="Argon"
		>
			<template v-slot:links>
				<sidebar-item
					:link="{
						name: 'Dashboard',
						icon: 'ni ni-tv-2 text-primary',
						path: '/dashboard',
					}"
				/>
			</template>
		</side-bar>
		<div class="main-content" :data="sidebarBackground">
			<dashboard-navbar :me="me"></dashboard-navbar>
			<div @click="toggleSidebar">
				<router-view v-slot="{ Component }">
					<Transition name="fade">
						<div>
							<component :is="Component"></component>
							<loader></loader>
						</div>
					</Transition>
				</router-view>
				<content-footer v-if="!$route.meta.hideFooter"></content-footer>
			</div>
		</div>
	</div>
</template>
<script>
	import DashboardNavbar from "./DashboardNavbar.vue";
	import ContentFooter from "./ContentFooter.vue";
	import Loader from "../components/Loader.vue";
	// import {mapGetters} from "vuex";

	export default {
		components: {
			DashboardNavbar,
			ContentFooter,
			Loader,
		},
		data() {
			return {
				sidebarBackground: "vue", //vue|blue|orange|green|red|primary
				me: null,
			};
		},
		async created() {
			await this.$store.dispatch("me");
			this.me = await this.$store.getters["me"];
		},
		methods: {
			toggleSidebar() {
				if (this.$sidebar.showSidebar) {
					this.$sidebar.displaySidebar(false);
				}
			},
		},
		computed: {
			// ...mapGetters(['me'])
		},
	};
</script>
<style lang="scss"></style>
