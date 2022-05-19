<template>
	<div class="card">
		<div class="card-header border-0">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="mb-0">Usuarios</h3>
				</div>
				<div class="col text-right">
					<a
						href="#"
						class="btn btn-sm btn-default"
						@click.prevent="handleAdd()"
						>Agregar</a
					>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<base-table thead-classes="thead-light" :data="tableData">
				<template v-slot:columns>
					<th>Nombre</th>
					<th>Apellido</th>
					<th>Email</th>
					<th>Móvil</th>
					<th>Tipo de cliente</th>
					<th>Acciones</th>
				</template>

				<template v-slot:default="row">
					<th scope="row">
						{{ row.item.name }}
					</th>
					<td>
						{{ row.item.last_name }}
					</td>
					<td>
						{{ row.item.email }}
					</td>
					<td>
						{{ row.item.phone_number }}
					</td>
					<td>
						{{ row.item.type.name }}
					</td>
					<td>
						<a
							href="#"
							class="btn btn-sm btn-default"
							@click.prevent="handleView(row.item.id)"
							><i class="fa-regular fa-eye"></i
						></a>
						<a
							href="#"
							class="btn btn-sm btn-default"
							@click.prevent="destroy(row.item.id)"
							><i class="fa-solid fa-trash-can"></i
						></a>
					</td>
				</template>
			</base-table>
			<div>
				<base-pagination
					:perPage="this.metaData.perPage"
					:value="this.page"
					@changePage="handleChange($event)"
					:total="this.metaData.total"
					align="center"
				></base-pagination>
			</div>
		</div>

		<Transition name="fade">
			<modal
				v-model:show="this.modal"
				model="delegado"
				:action="action"
				@close="action = 'Registrar'"
				modalClasses="modal-xl"
				v-if="this.modal"
			>
                <form-user :id="user_id" @close="modal = false" @reload="index()"></form-user>
				<template v-slot:footer> </template>
			</modal>
		</Transition>
	</div>
</template>

<script>
	import service from "../../store/services/auth-service";
import FormUser from '../Forms/FormUser.vue';

	export default {
	components: { FormUser },
		name: "user-table",
		data() {
			return {
				tableData: {},
				metaData: {},
				page: 1,
				modal: false,
				submit: false,
				user_id: null,
				disabled: false,
				action: "Registrar",
			};
		},
		mounted() {
			this.index(this.page);
		},
		methods: {
			async index(page = 1) {
				const response = await service.index(page, `&includes[]=type`);
				this.tableData = response.data.data;
				this.metaData = response.data.meta.page;
			},
			async destroy(id) {
				try {
					await service.destroy(id);
					this.$swal("Recurso eliminado", '', 'success');
                    this.index()
				} catch (err) {
                    console.log(err);
                }
			},
			async handleChange(event) {
				if (event != this.page) {
					this.page = event;
					this.index(event);
				}
			},
			async handleView(id) {
				this.user_id = id;
				this.action = "editar";
				this.disabled = true;
				this.modal = true;
			},
			handleAdd() {
				this.user_id = null;
				this.disabled = false;
				this.modal = true;
			},
		},
		watch: {
			modal(newVal) {
				if (newVal == false) {
					this.action = "Registrar";
				}
			},
		},
	};
</script>
<style></style>
