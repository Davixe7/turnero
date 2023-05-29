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
                <template v-for="service in services" :key="service.id" v-if="services.length">
                    <OrderServiceCard
                        :service="service"
                        @click="take(service.order.id, service.service_id)">
                    </OrderServiceCard>
                </template>
                <div v-else class="text-center q-pa-xl">
                    <q-icon name="task_alt" size="128px" color="grey-3"></q-icon>
                    <div class="text-h6 text-grey-3">
                        No tienes servicios pendientes
                    </div>
                </div>
            </q-tab-panel>
            <q-tab-panel name="t_1" class="q-pa-none">
                <OrderServiceCard
                    v-if="service"
                    :mode="'taken'"
                    :service="service">
                </OrderServiceCard>
                <div v-else class="text-center q-pa-xl">
                    <q-icon name="task_alt" size="128px" color="grey-3"></q-icon>
                    <div class="text-h6 text-grey-3">
                        No tienes servicios en curso
                    </div>
                </div>
            </q-tab-panel>
            <q-tab-panel name="t_2" class="q-pa-none">
                <template v-for="service in history" :key="service.finished_at" v-if="history.length">
                    <OrderServiceCard :service="service" :mode="'history'">
                    </OrderServiceCard>
                </template>
                <div v-else class="text-center q-pa-xl">
                    <q-icon name="task_alt" size="128px" color="grey-3"></q-icon>
                    <div class="text-h6 text-grey-3">
                        Aún no hay historial
                    </div>
                </div>
            </q-tab-panel>
        </q-tab-panels>
    </div>
</template>

<script setup>
import { router, useForm } from '@inertiajs/vue3';
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
    auth: Object,
    service: {
        type: Object,
        default: () => ({})
    }
})

const services = ref([])

function take(orderId, serviceId) {
    if( props.service?.id ) return
    const form = useForm({ 'order_id': orderId, 'service_id': serviceId })
    form.put('/order_services/take', {
        onSuccess: () => $q.notify('Servicio asignado con éxito')
    })
}

watch(() => props.services, (newVal) => {
    services.value = [...newVal]
})

watch(() => props.service, (newVal) => {
    tab.value = newVal?.id ? 't_1' : 't_0'
})

onMounted(()=>{
    if (props.service) tab.value = 't_1'

    services.value = [...props.services]

    Echo.channel(`users.${props.auth.user.user_id}.services`)
    .listen('ServiceAvailable', (e) => {
        services.value.push(e.service)
    })
    .listen('ServiceTaken', (e)=>{
        router.reload({ only: ['services'], preserveScroll: true })
    });
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
