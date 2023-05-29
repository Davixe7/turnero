<template>
    <div id="scan">
        <q-card>
            <div class="flex">
                <q-card-section>
                    <div class="text-h5 q-py-md">
                        Scanée el código para continuar
                    </div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.<br> Consectetur reprehenderit, rem veniam possimus</p>
                </q-card-section>
                <q-card-section class="flex column q-ml-auto">
                    <q-img :width="'350px'" :height="'350px'" :src="qr"></q-img>
                    <q-btn color="primary" :loading="form.processing">Actualizar</q-btn>
                </q-card-section>
            </div>
        </q-card>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    qr: String,
    errors: null
})

const form = useForm({})
</script>
