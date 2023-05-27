<template>
    <q-card>
        <q-card-section>
            <div>
                Nombre
            </div>
            <div>
                {{ order.client.name }}
            </div>
        </q-card-section>
        <q-card-section>
            <div>
                Teléfono
            </div>
            <div>
                {{ order.client.phone }}
            </div>
        </q-card-section>
        <q-card-section>
            <div>
                Cédula
            </div>
            <div>
                {{ order.client.dni }}
            </div>
        </q-card-section>
    </q-card>

    <q-table
        title="Servicios"
        :columns="columns"
        :rows="rows"
        :search="search">
        <template v-slot:body-cell-actions="props">
            <q-td class="text-grey flex justify-end">
                <Link class="text-grey" :href="`/employees/${props.row.id}/edit`">
                    <q-btn flat round size="12px" icon="edit">
                    </q-btn>
                </Link>
            </q-td>
        </template>
    </q-table>
</template>

<script setup>
import { onMounted, ref } from 'vue';

    const props   = defineProps({order: Object})
    const rows    = ref([])
    const columns = ref([
        {name: 'name', field: 'name', label: 'Servicio', align: 'left'},
        {name: 'taken_at', field: 'taken_at', label: 'Tomado', align: 'left'},
        {name: 'finished_at', field: 'finished_at', label: 'Finalizado', align: 'left'},
        {name: 'taken_by', field: row => row.employee.name, label: 'Encargado', align: 'left'},
        {name: 'status', field: 'state', label: 'Estado', align: 'left'},
        {name: 'actions', align: 'right'},
    ])

    onMounted(()=>{
        rows.value = [...props.order.services]
    })
</script>
