<template>
    <div id="login">
        <div class="row">
            <div class="col col-md-4 q-mx-auto">
                <form @submit.prevent="submit">
                    <q-card>
                        <q-card-section class="text-subtitle1 text-weight-bold text-center">Control de porterias
                        </q-card-section>
                        <q-card-section class="q-gutter-y-md">
                            <q-input v-model="email" label="Nombre de usuario o Email" :error="Boolean(errors?.email)"
                                :error-message="errors?.email">
                            </q-input>
                            <q-input v-model="password" label="Contraseña" type="password" :error="Boolean(errors?.password)"
                                :error-message="errors?.password"></q-input>
                        </q-card-section>
                        <q-card-actions>
                            <q-btn type="submit" flat class="q-ml-auto">Ingresar</q-btn>
                        </q-card-actions>
                    </q-card>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue';

const props = defineProps({
    email: String,
    password: String,
    errors: Object
})

const email = ref('')
const password = ref('')

function submit() {
    router.post('/login', { email: email.value, password: password.value })
}

onMounted(() => {
    email.value = props.email
    password.value = props.password
})
</script>
