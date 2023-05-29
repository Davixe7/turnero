<template>
    <div id="login">
        <div class="row">
            <div class="col col-md-4 q-mx-auto">
                <form @submit.prevent="submit">
                    <q-card bordered flat style="max-width: 420px; margin: 10vh auto;">
                        <q-card-section class="text-h6 text-weight-bold text-center">
                            Acceda a sus servicios
                        </q-card-section>
                        <q-card-section class="q-gutter-y-md">
                            <q-input v-model="form.email" label="Nombre de usuario o Email" :error="Boolean(form.errors.email)"
                                :error-message="form.errors.email">
                            </q-input>
                            <q-input v-model="form.password" label="Contraseña" type="password" :error="Boolean(form.errors.password)"
                                :error-message="form.errors.password"></q-input>
                        </q-card-section>
                        <q-card-actions>
                            <q-btn
                                :loading="form.processing"
                                type="submit"
                                color="primary"
                                class="q-ml-auto full-width">
                                Ingresar
                            </q-btn>
                        </q-card-actions>
                    </q-card>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: ''
})

function submit() {
    form.post('/login')
}
</script>

