<template>
    <div id="clients">
        <q-table
            title="Clientes"
            :columns="columns"
            :rows="rows"
            :search="search">
            <template v-slot:body-cell-actions="props">
                <q-td class="text-grey flex justify-end">
                    <Link class="text-grey" :href="`/clients/${props.row.id}/edit`">
                        <q-btn flat round size="12px" icon="message">
                        </q-btn>
                    </Link>
                </q-td>
            </template>
        </q-table>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
const props = defineProps({clients: Array})
const search = ref('')
const rows = ref([])
const columns = ref([
    {name:'dni', field: 'dni', label: 'Cédula', align: 'left'},
    {name:'phone', field: 'phone', label: 'Teléfono', align: 'left'},
    {name:'name', field: 'name', label: 'Nombre', align: 'left'},
    {name:'actions', align: 'right'},
])
onMounted(()=>rows.value = [...props.clients])
</script>
