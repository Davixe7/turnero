<template>
    <div id="create-form-field">
        <q-card flat bordered>
            <q-card-section class="text-h6 text-weight-regular">
                Personalizar formulario de Ordenes
            </q-card-section>
            <q-card-section class="q-gutter-y-md">
                <q-card v-for="formField in fields" flat bordered>
                    <q-card-section class="flex items-center q-py-sm">
                        <div class="text-weight-bold">
                            {{ formField.label ? formField.label : 'Añadir etiqueta' }}
                        </div>
                        <div class="q-ml-auto text-grey">
                            <q-btn size="12px" flat round :icon="formField.expanded ? 'expand_less' : 'expand_more'"
                                @click="formField.expanded = !formField.expanded"></q-btn>
                            <q-btn size="12px" flat round icon="delete" @click="deleteField(formField)"
                                :loading="deleteFieldForm.process"></q-btn>
                        </div>
                    </q-card-section>
                    <q-separator></q-separator>
                    <q-slide-transition>
                        <q-card-section v-show="formField.expanded" class="q-gutter-y-md">
                            <q-input stack-label v-model="formField.name" label="Nombre"></q-input>
                            <q-input stack-label v-model="formField.label" label="Etiqueta"></q-input>
                            <q-checkbox class="q-pl-none" v-model="formField.required" label="Obligatorio"></q-checkbox>
                        </q-card-section>
                    </q-slide-transition>
                </q-card>
                <q-card flat unelevated bordered>
                    <q-card-section class="flex justify-center">
                        <q-btn flat round icon="add" @click="addNew"></q-btn>
                    </q-card-section>
                </q-card>
            </q-card-section>
            <q-card-section>
                <div class="flex">
                    <q-btn unelevated class="q-ml-auto" color="primary" @click="update">Actualizar</q-btn>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';

const props = defineProps({
    formFields: {
        type: Array,
        default: []
    }
});

const fields = ref([])
const deleteFieldForm = useForm({})

function update() {
    router.post('/form_fields', { form_fields: fields.value })
}

function deleteField(field) {
    if (field.id) {
        deleteFieldForm.delete(`/form_fields/${field.id}`)
    }
    fields.value.splice(fields.value.indexOf(field), 1)
}

function addNew() {
    fields.value.push({
        name: '',
        label: '',
        required: false,
        expanded: true
    })
}

function setFields() {
    fields.value = JSON.parse(JSON.stringify(props.formFields))
    fields.value = fields.value.map(field => ({ ...field, required: Boolean(field.required) }))
}

watch(() => props.formFields, (newVal) => setFields())
onMounted(() => { setFields() })
</script>
