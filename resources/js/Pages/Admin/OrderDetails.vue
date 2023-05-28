<template>
    <q-card>
        <q-card-section>
            <div class="text-h6 text-weight-regular">
                Detalles de la orden
            </div>
        </q-card-section>
        <q-separator></q-separator>
        <q-card-section class="flex justify-between">
            <div>
                Nombre
            </div>
            <div class="text-caption">
                {{ order.client.name }}
            </div>
        </q-card-section>
        <q-card-section class="flex justify-between">
            <div>
                Teléfono
            </div>
            <div class="text-caption">
                {{ order.client.phone }}
            </div>
        </q-card-section>
        <q-card-section class="flex justify-between">
            <div>
                Cédula
            </div>
            <div class="text-caption">
                {{ order.client.dni }}
            </div>
        </q-card-section>
        <q-card-section v-for="field in order.fields" class="flex justify-between">
            <div>
                {{ field.label }}
            </div>
            <div class="text-caption">
                {{ field.pivot.value }}
            </div>
        </q-card-section>
    </q-card>

    <q-table
        title="Servicios"
        :columns="columns"
        :rows="rows">
        <template v-slot:body-cell-status="props">
            <q-td>
                <div v-if="props.row.state != 'pending'">
                    {{ props.row.state == 'success' ? 'Aprobado' : 'Rechazado' }}
                </div>
                <div v-else-if="props.row.taken_at">
                    <q-spinner-clock
                        color="primary"
                        size="24px">
                    </q-spinner-clock>
                </div>
                <div v-else>
                    Pendiente
                </div>
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
        {name: 'taken_by', field: row => row.employee?.name, label: 'Encargado', align: 'left'},
        {name: 'status', field: 'state', label: 'Estado', align: 'left'},
        {name: 'actions', align: 'right'},
    ])

    onMounted(()=>{
        rows.value = [...props.order.services]
    })
</script>
