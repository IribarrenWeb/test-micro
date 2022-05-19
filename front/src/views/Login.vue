<template>
	<div class="row justify-content-center">
		<div class="col-lg-5 col-md-7">
			<div class="card bg-secondary shadow border-0">
				<div class="card-header bg-transparent pb-5">
					<div class="text-muted text-center mt-2 mb-3">
						<small>Login</small>
					</div>
				</div>
				<div class="card-body px-lg-5 py-lg-5">
					<form role="form" @submit.prevent="handleSubmit">
						<base-input
							formClasses="input-group-alternative mb-3"
							placeholder="Email"
							addon-left-icon="ni ni-email-83"
							v-model="model.email"
						>
						</base-input>

						<base-input
							formClasses="input-group-alternative mb-3"
							placeholder="Password"
							type="password"
							addon-left-icon="ni ni-lock-circle-open"
							v-model="model.password"
						>
						</base-input>

						<div class="text-center">
                            <button class="btn btn-primary my-4"  @click.prevent="handleSubmit">Login</button>
							<!-- <base-button type="primary" class="my-4" @click.prevent="handleSubmit"
								>Login</base-button -->
							<!-- > -->
						</div>
					</form>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-6 text-right">
					<router-link to="/register" class="text-light"
						><small>Crear nueva cuenta</small></router-link
					>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: "login",
		data() {
			return {
				model: {
					email: "keinher@test.com",
					password: "password",
				},
			};
		},
		methods: {
			handleSubmit() {
				console.log("click");
				const user = {
					email: this.model.email,
					password: this.model.password,
				};

				try {
                    this.$store.dispatch("login", {
                        user,
					}).then(() => {
                        this.$router.push({name: 'dashboard'})
                    });
				} catch (error) {
					// await this.$swal("Error", "Credenciales invalidas!");
				}
			},
		},
	};
</script>
<style></style>
