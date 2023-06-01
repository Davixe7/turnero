<template>
    <div id="create-form-field">
        <q-card flat bordered>
            <q-card-section class="text-h6 text-weight-regular">
                Personalizar formulario de Ordenes
            </q-card-section>
            <q-card-section class="q-gutter-y-md">
                <div
                    v-for="formField in fields"
                    draggable="true"
                    @dragstart="dragged=formField"
                    @dragover.prevent="()=>{}"
                    @drop="onDrop($event, formField)">
                    <q-card flat bordered>
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
                        <q-separator v-show="formField.expanded"></q-separator>
                        <q-slide-transition>
                            <q-card-section v-show="formField.expanded" class="q-gutter-y-md">
                                <q-input stack-label v-model="formField.name" label="Nombre"></q-input>
                                <q-input stack-label v-model="formField.label" label="Etiqueta"></q-input>
                                <q-checkbox class="q-pl-none" v-model="formField.required" label="Obligatorio"></q-checkbox>
                                <q-checkbox class="q-ml-md" v-model="formField.is_identifier" label="Aparece en el título"></q-checkbox>
                            </q-card-section>
                        </q-slide-transition>
                    </q-card>
                </div>

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

const dragged = ref(null)
const fields  = ref([])
const deleteFieldForm = useForm({})

function onDrop(event, item){
    if( item == dragged.value ) return

    fields.value.splice( fields.value.indexOf(dragged.value), 1 )
    let upperSpan = event.target.getBoundingClientRect().bottom - (event.target.clientHeight / 2)
    let start = fields.value.indexOf(item);

    if( event.y < upperSpan){
        fields.value.splice( start, 0, dragged.value )
    }else {
        start == fields.value.length - 1
        ? fields.value.push( dragged.value )
        : fields.value.splice( start + 1, 0, dragged.value )
    }
  }

function update() {
    fields.value.forEach((item, index)=>item.index = index)
    router.post('/form_fields', { form_fields: fields.value })
}

function deleteField(field) {
    if (field.id) {
        deleteFieldForm.delete(`/form_fields/${field.id}`)
    }
    fields.value.splice(fields.value.indexOf(field), 1)
    fields.value.forEach((item, index)=>item.index = index)
}

function addNew() {
    fields.value.push({
        name: '',
        label: '',
        required: false,
        expanded: true,
        index: fields.value.length + 1,
        is_identifier: false
    })
}

function setFields() {
    fields.value = JSON.parse(JSON.stringify(props.formFields))
    fields.value = fields.value.map(field => ({ ...field, required: Boolean(field.required), is_identifier: Boolean(field.is_identifier) })).sort((a,b) => a.index - b.index)
}

watch(() => props.formFields, (newVal) => setFields())
onMounted(() => { setFields() })
</script>
