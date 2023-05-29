<template>
    <div id="orders">
        <q-table
            title="Ordenes"
            :columns="columns"
            :rows="rows"
            :search="search">
            <template v-slot:body-cell-actions="props">
                <q-td class="text-grey flex justify-end">
                    <Link class="text-grey" :href="`/orders/${props.row.id}`">
                        <q-btn flat round size="12px" icon="remove_red_eye">
                        </q-btn>
                    </Link>
                </q-td>
            </template>
        </q-table>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
const props = defineProps({
    orders: Array,
    auth: Object
})
const search = ref('')
const rows = ref([])
const columns = ref([
    {name:'created_at', field: 'created_at', label: 'Creada el', align: 'left'},
    {name:'client', field: row=>row.client.name, label: 'Nombre', align: 'left'},
    {name:'services', field: row=>row.services.length, label: 'Servicios', align: 'left'},
    {name:'status', field: 'status', label: 'Estado', align: 'left'},
    {name:'actions', align: 'right'},
])

watch(()=>props.orders, ()=> rows.value = [...props.orders])

onMounted(()=>{
    rows.value = [...props.orders]
    Echo.channel(`users.${props.auth.user.id}.orders`)
    .listen('OrderCreated', function(e){
        rows.value.unshift(e.order)
    })
    .listen('OrderUpdated', function(e){
        router.reload({only:['orders']})
    })
})
</script>
