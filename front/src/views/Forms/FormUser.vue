<template>
  <div>
    <form-validate @submit="onSubmit" ref="form" v-slot="{meta}" :initial-values="current_values">

      <div class="row border rounded border-light px-md-3 py-md-2">
        <div class="col-12">
          <h4>
            Datos generales
          </h4>
          <hr>
        </div>
        <div class="col-lg-4">
          <base-field label="Nombre">
            <field-validate class="form-control" name="name" label="nombre" rules="required|max:20|alpha_spaces" v-model="model.name"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="last_name" label="Apellido">
            <field-validate class="form-control" name="last_name" label="apellido" rules="required|max:20|alpha_spaces" v-model="model.last_name"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="phone_number" label="Móvil">
            <field-validate class="form-control" name="phone_number" label="móvil" rules="required|min:7|max:15" v-model="model.phone_number"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="company" label="company">
            <field-validate class="form-control" name="company" label="company" rules="required" v-model="model.company"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="email" label="Email">
            <field-validate class="form-control" name="email" label="email" rules="required|email" v-model="model.email"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="password" label="Password">
            <field-validate class="form-control" name="password" label="password" :rules="{'required':!update}" type="password" v-model="model.password"/>
          </base-field>
        </div>

        <div class="col-lg-4">
          <base-field name="type_client_id" label="Tipo">
            <div v-if="update && !type_update" class="d-flex">
              <input type="text" class="form-control mr-md-3" disabled :value="model.type.name">
              <base-button @click="type_update = true" size="sm" type="default" :outline="true"><i class="fa-solid fa-pencil"></i></base-button>
            </div>
            <div v-show="!update || type_update">
              <field-validate class="form-control" as="select" name="type_client_id" :rules="{'required':!update || type_update}" label="Tipo" v-model="model.type_client_id">
                  <option value="" selected>Selecciona un tipo</option>
                  <option v-for="type in types" :key="type.id" :value="type.id">{{type.name}}</option>
              </field-validate>
              <base-button v-if="update" @click="reset('type')" size="sm" type="default" :outline="true"><i class="fa-solid fa-rotate-left"></i></base-button>
            </div>
          </base-field>
        </div>

        <div class="col-lg-4" v-if="model.type_client_id == 3">
          <base-field name="type_desc" label="Tipo de cliente">
            <field-validate class="form-control" name="type_desc" label="type_desc" rules="required" v-model="model.type_desc"/>
          </base-field>
        </div>

      </div>

      <div class="d-flex justify-content-lg-end">
        <base-button
          type="default"
          nativeType="submit"
          size="sm"
          v-if="!update"
          :disabled="!meta.valid"
        >
          Enviar
        </base-button>
        <base-button
          type="default"
          nativeType="submit"
          size="sm"
          v-if="update"
          :disabled="!meta.valid || !meta.dirty"
        >
          Actualizar
        </base-button>
        <base-button
          type="default"
          :outline="true"
          size="md"
          class="btn-inline-block"
          @click="$emit('close')"
        >
          Cancelar
        </base-button>
      </div>
    </form-validate>

  </div>
</template>
<script>
import BaseField from '../../components/BaseField.vue';

  import service from "../../store/services/auth-service";
  export default {
	components: { BaseField },
    name: "form-auditor",
    props: {
      id: {
        default: null,
        required: false
      },
    },
    data() {
      return {
        errors: {},
        model: {
            name: "",
            last_name: "",
            type_client_id: "",
            type_desc: "",
            type: "",
            email: "",
            password: "",
            company: "",
        },
        user: null,
        types: null,
        current_values: null,
        type_update: null
      };
    },
    mounted() {
      this.loadTypes()
    },
    methods: {
      async onSubmit(values, { resetForm }) {
        let response = null
        try {
          if (!this.update) {
            response = await service.store(this.model);
          }else{
            response = await service.update(this.model.id, this.model);
          }
          if (response.status == 201) {
            if (!this.update) {
                resetForm();
                this.$emit("close");
              }else{
                this.show(this.model.id)
              }
              this.$emit("reload");
          }
        } catch (err) {
         console.log(err);
        }
      },
      async loadTypes(){
            const res = await service.types()
            this.types = res.data.data;
        },
      async show(id){
        console.log(this.$refs)
        try {
          const response = await service.show(
            id,
            "includes[]=type"
          );
          const data = response.data.data
          this.setCurrent(data)
          this.model = data
        } catch (err) {
          this.$emit('close')
          console.log(err);
        }
      },
      setCurrent(data) {
        let current = null
        if(this.update){
          current = {
            name: data.name,
            last_name: data.last_name,
            phone_number: data.phone_number,
            type_client_id: data.type_client_id,
            email: data.email,
            company: data.company,
          }
        }
        this.current_values = current
        this.type_update = false
      },
      
    },
    computed: {
      update(){
        return this.id != null 
      },
    },
    watch: {
      id: {
        // the callback will be called immediately after the start of the observation
        handler (val) {
          if (val >= 1 && val != null) {
            this.show(val)
          }
        },
        immediate: true, 
      }
    }
  };
</script>
