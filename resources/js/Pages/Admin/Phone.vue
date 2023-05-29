<template>
    <div id="scan">
        <q-card>
            <q-card-section>
                <div class="text-h6 text-weight-regular">
                    Asocie un número telefónico
                </div>
            </q-card-section>
            <q-card-section>
                <q-input stack-label outlined label="Número de teléfono" v-model="form.phone"></q-input>
            </q-card-section>
            <q-card-actions class="flex justify-end">
                <q-btn
                    color="primary"
                    :loading="form.processing"
                    @click="form.put('/messages/update-phone')">
                    Guardar
                </q-btn>
            </q-card-actions>
        </q-card>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    phone: null
})
</script>
