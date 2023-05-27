<template>
    <div id="employee-dashboard">
        <div class="page-title">
            Turnos
        </div>
        <q-tabs breakpoint="0" indicator-color="primary" :shrink="true" v-model="tab" :align="'left'">
            <q-tab v-for="tab in tabs">{{ tab }}</q-tab>
        </q-tabs>

        <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="t_0" class="q-pa-none">
                <template v-for="service in services" :key="service.id">
                    <OrderServiceCard
                        :service="service"
                        @click="take(service.order.id, service.service_id)">
                    </OrderServiceCard>
                </template>
            </q-tab-panel>
            <q-tab-panel name="t_1" class="q-pa-none">
                <OrderServiceCard
                    v-if="service"
                    :mode="'taken'"
                    :service="service">
                </OrderServiceCard>
            </q-tab-panel>
            <q-tab-panel name="t_2" class="q-pa-none">
                <template v-for="service in history" :key="service.finished_at">
                    <OrderServiceCard :service="service" :mode="'history'">
                    </OrderServiceCard>
                </template>
            </q-tab-panel>
        </q-tab-panels>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { onMounted, ref, watch } from 'vue';
import OrderServiceCard from './OrderServiceCard.vue';

const $q   = useQuasar()
const tab  = ref('t_0')
const tabs = ref([
    'Pendientes',
    'En curso',
    'Historial'
])

const props = defineProps({
    history: Array,
    services: Array,
    service: {
        type: Object,
        default: () => ({})
    }
})

function take(orderId, serviceId) {
    if( props.service?.id ) return
    const form = useForm({ 'order_id': orderId, 'service_id': serviceId })
    form.put('order_services/take', {
        onSuccess: () => $q.notify('Servicio asignado con éxito')
    })
}

onMounted(()=>{
    if (props.service) tab.value = 't_1'
})
watch(() => props.service, (newVal) => {
    tab.value = newVal?.id ? 't_1' : 't_0'
})
</script>

<style lang="scss">
.page-title {
    font-size: 18px;
    margin-bottom: 1em;
}

#employee-dashboard {
    .q-tabs {
        margin-bottom: 40px;
    }

    .q-tab {
        &:first-child {
            padding-left: 0;
        }

        &__content {
            font-size: 13px;
            font-weight: 500;
            letter-spacing: .02em;
        }
    }
}
</style>
