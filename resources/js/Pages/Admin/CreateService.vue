<template>
    <q-card>
        <form @submit.prevent="submit">
            <q-card-section>
                <div class="text-h6 text-weight-regular">
                    {{ form.id ? 'Actualizar' : 'Registrar' }} servicio
                </div>
            </q-card-section>
            <q-card-section class="q-gutter-y-md">
                <q-input stack-label label="Nombre" :error="form.errors.name" :error-message="form.errors.name"
                    v-model="form.name">
                </q-input>
            </q-card-section>
            <q-card-actions class="flex justify-end">
                <q-btn flat type="submit" :loading="form.processing">Guardar</q-btn>
            </q-card-actions>
        </form>
    </q-card>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { onMounted, watch } from 'vue';

const props = defineProps({service: Object})
const $q = useQuasar()
const form = useForm({
    name: ''
})

function submit(){
    form.id
    ? form.put(`/services/${form.id}`, {onSuccess:()=>$q.notify('Actualizado exitosamente!')})
    : form.post('/services', {onSuccess:()=>$q.notify('Registrado exitosamente!')})
}
function setService(){
    form.defaults({...props.service})
    form.reset()
}
onMounted(()=>setService())
watch(()=>props.service, (newVal)=>{setService()})
</script>
