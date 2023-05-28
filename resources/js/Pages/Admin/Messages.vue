<template>
    <div id="messages">
        <div class="row q-col-gutter-x-lg">
            <div class="col col-md-3">
                <q-card>
                    <q-list>
                        <q-item v-for="client in clients">
                            <q-item-section>
                                {{ client.name }}
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-md-6">
                <q-card>
                    <q-card-section>
                        <q-input
                            type="textarea"
                            label="Mensaje"
                            v-model="form.body"
                            :error="Boolean(form.errors.body)"
                            :error-message="form.errors.body"
                            >
                        </q-input>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col col-md-3">
                <q-card></q-card>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    clients: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    body: '',
    recepients: []
})
</script>
