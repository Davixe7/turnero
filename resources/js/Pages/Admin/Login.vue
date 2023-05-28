<template>
    <div id="login">
        <div class="row">
            <div class="col col-md-4 q-mx-auto q-pt-xl">
                <form @submit.prevent="submit">
                    <q-card flat bordered>
                        <q-card-section class="text-h6 text-weight-regular text-center">
                            Panel de Administración
                        </q-card-section>
                        <q-card-section class="q-gutter-y-md">
                            <q-input outlined v-model="form.email" label="Nombre de usuario o Email" :error="Boolean(form.errors.email)"
                                :error-message="form.errors?.email">
                            </q-input>
                            <q-input outlined v-model="form.password" label="Contraseña" type="password" :error="Boolean(form.errors.password)"
                                :error-message="form.errors?.password"></q-input>
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
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: ''
})

function submit() {
    form.post('/login')
}
</script>
