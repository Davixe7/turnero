<template>
    <div id="create-employee">
        <q-card>
            <form @submit.prevent="submit">
                <q-card-section>
                    <div class="text-h6 font-weight-regular">
                        {{ form.id ? 'Actualizar' : 'Registrar' }} usuario
                    </div>
                </q-card-section>
                <q-card-section class="q-gutter-y-md">
                    <q-input
                        stack-label
                        label="Nombre"
                        :error="form.errors.name"
                        :error-message="form.errors.name"
                        v-model="form.name">
                    </q-input>

                    <q-input
                        stack-label
                        label="Email"
                        :error="form.errors.email"
                        :error-message="form.errors.email"
                        v-model="form.email">
                    </q-input>

                    <q-input
                        stack-label
                        label="Contraseña"
                        :error="form.errors.password"
                        :error-message="form.errors.password"
                        v-model="form.password">
                    </q-input>
                </q-card-section>
                <q-card-actions class="flex justify-end">
                    <q-btn flat type="submit" :loading="form.processing">Guardar</q-btn>
                </q-card-actions>
            </form>
        </q-card>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { onMounted, watch } from 'vue';

const props   = defineProps({ user: Object})
const $q      = useQuasar()
const form    = useForm({
    name: '',
    email: '',
    password: ''
})

function submit() {
    !form.id
        ? form.post('/root/users', { onSuccess: () => $q.notify('Registrado exitosamente!'), only: ['user'] })
        : form.put(`/root/users/${form.id}`, { onSuccess: () => $q.notify('Actualizado exitosamente!'), only: ['user'] })
}
function setUser() {
    if( !props.user || !props.user.id ) return
    form.defaults(JSON.parse(JSON.stringify({ ...props.user })))
    form.reset()
}
onMounted(() => setUser())
watch(() => props.user, () => { setUser() })
</script>
