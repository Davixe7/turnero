<template>
    <div id="create-employee">
        <q-card>
            <form @submit.prevent="submit">
                <q-card-section class="text-h6 text-weight-regular">
                    {{ form.id ? 'Actualizar' : 'Registrar' }} empleado
                </q-card-section>
                <q-card-section class="q-gutter-y-md">
                    <q-input stack-label label="Nombre" :error="form.errors.name" :error-message="form.errors.name"
                        v-model="form.name">
                    </q-input>

                    <q-input stack-label label="Email" :error="form.errors.email" :error-message="form.errors.email"
                        v-model="form.email">
                    </q-input>

                    <q-input stack-label label="Contraseña" :error="form.errors.password"
                        :error-message="form.errors.password" v-model="form.password">
                    </q-input>
                </q-card-section>
                <q-card-actions class="flex justify-end">
                    <q-btn flat type="submit" :loading="form.processing">Guardar</q-btn>
                </q-card-actions>
            </form>
        </q-card>

        <q-card v-if="services && services.length">
            <q-card-section>
                <div class="text-h6 text-weight-regular">
                    Seleccione servicios
                </div>
            </q-card-section>
            <q-card-section>
                <q-option-group type="checkbox"
                    :options="options"
                    v-model="form.employments">
                </q-option-group>
            </q-card-section>
            <q-card-actions class="flex justify-end">
                <q-btn flat type="submit" :loading="form.processing" @click="submit">Guardar</q-btn>
            </q-card-actions>
        </q-card>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { onMounted, watch } from 'vue';

const options = props.services.map(service => ({ label: service.name, value: service.id }))
const props   = defineProps({ employee: Object, services: Array })
const $q      = useQuasar()
const form    = useForm({
    name: '',
    email: '',
    password: '',
    employments: []
})

function submit() {
    !form.id
        ? form.post('/employees', { onSuccess: () => $q.notify('Registrado exitosamente!'), only: ['employee'] })
        : form.put(`/employees/${form.id}`, { onSuccess: () => $q.notify('Actualizado exitosamente!'), only: ['employee'] })
}
function setEmployee() {
    if( !props.employee || !props.employee.id ) return
    form.defaults(JSON.parse(JSON.stringify({ ...props.employee })))
    form.reset()
    form.employments = props.employee.employments.map(job => job.id)
}
onMounted(() => setEmployee())
watch(() => props.employee, (newVal) => { setEmployee() })
</script>
