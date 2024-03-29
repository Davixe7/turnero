<template>
    <div id="create-order">
        <q-card>
            <q-card-section>
                <div class="text-h6 text-weight-regular">
                    Registar Orden
                </div>
            </q-card-section>
            <q-separator></q-separator>
            <q-stepper
                    v-model="step"
                    color="primary"
                    ref="stepper"
                    :done="step > 1">
                    <q-step
                        :name="1"
                        title="Información básica"
                        icon="edit">
                        <form ref="firstStepForm">
                            <q-select
                                label="Seleccionar comercio"
                                stack-label
                                :map-options="true"
                                :emit-value="true"
                                :options="admins"
                                option-value="id"
                                option-label="name"
                                v-model="form.user_id"
                                :error="Boolean(form.errors.user_id)"
                                :error-message="form.errors.user_id">
                            </q-select>

                            <q-input
                                stack-label
                                label="Cédula"
                                v-model="form.dni"
                                :error="Boolean(form.errors.dni)"
                                :error-message="form.errors.dni"
                                required>
                            </q-input>

                            <q-input
                                stack-label
                                label="Nombre"
                                v-model="form.name"
                                :error="Boolean(form.errors.name)"
                                :error-message="form.errors.name"
                                required>
                            </q-input>

                            <q-input
                                stack-label
                                label="Teléfono"
                                v-model="form.phone"
                                :error="Boolean(form.errors.phone)"
                                :error-message="form.errors.phone"
                                required>
                            </q-input>
                        </form>
                    </q-step>
                    <q-step :name="2" title="Descripción" :done="step > 3" icon="description">
                        <form ref="secondStepForm" class="q-gutter-y-md">
                            <template v-for="formField in formFields">
                                <q-input stack-label
                                    v-model="form.info[formField.id]"
                                    :label="formField.label"
                                    :required="formField.required">
                                </q-input>
                            </template>
                        </form>
                    </q-step>
                    <q-step :name="3" title="Seleccionar servicios" :done="step > 2" icon="shopping_bag">
                        <q-option-group
                            v-if="admin.services"
                            type="checkbox"
                            v-model="form.services"
                            :options="admin.services">
                        </q-option-group>
                    </q-step>
                    <template v-slot:navigation>
                        <q-stepper-navigation class="flex justify-end">
                            <q-btn v-if="step > 1" flat color="primary" @click="$refs.stepper.previous()" label="Volver" class="q-ml-sm" />
                            <q-btn v-if="step <= 4" @click="skipFormStep($refs.stepper)" color="primary" :label="'Continuar'" :loading="form.processing"/>
                        </q-stepper-navigation>
                    </template>
                </q-stepper>
        </q-card>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useQuasar } from 'quasar';

    const $q = useQuasar()
    const stepper = ref(null)
    const step = ref(1)
    const props = defineProps({admins: Array})
    const firstStepForm = ref(null)
    const secondStepForm = ref(null)

    const form = useForm({
        user_id: null,
        dni: '',
        phone: '',
        name: '',
        services: [],
        info: {}
    })

    function skipFormStep(stepper){
        if( step.value == 1 ){
            form.errors.user_id = null
            if( !form.user_id ){
                form.errors.user_id = 'Debe seleccionar un comercio primero'
                return
            }
            if(!firstStepForm.value.reportValidity()) return
        }

        if( step.value == 2 && !secondStepForm.value.reportValidity()) return
        if( step.value == 3){
            if(form.services.length < 1){
                $q.notify({message: 'Seleccione por lo menos un servicio', color: 'red'})
                return
            }
            submit()
        }

        stepper.next()
    }

    const admin = computed(() => {
        if( !form.user_id ) return null;
        let found = {...props.admins.find(needle => needle.id == form.user_id)}
        found.services = found.services.filter(service => service.index != '0')
        found.services = found.services.map(service => ({label: service.name, value: service.id}))
        return found;
    })

    function submit(){
        form.post('/orders', {
            onSuccess:()=>$q.notify('Orden creada exitosamente')
        })
    }

    const formFields = ref([])

    watch(admin, (newVal, oldVal)=>{
        fetch(`/form_fields/?user_id=${newVal.id}`)
        .then(response => response.json())
        .then(response => formFields.value = [...response.data])
    })
</script>
