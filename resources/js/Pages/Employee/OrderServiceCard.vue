<template>
    <q-card flat clickable v-ripple:green bordered class="relative-position q-mb-lg">
        <q-card-section class="flex">
            <div>
                <div class="text-weight-medium q-mb-xs" style="font-size: 17px; line-height: 1em;">
                    {{ service.order_id }} - {{ service.order.fields.filter(field => field.is_identifier).map(field=>field.pivot.value).join(' - ') }}
                </div>
                <div class="text-caption">
                    Servicio de {{ service.name }}
                </div>
            </div>
            <div class="q-ml-auto">
                <q-badge
                    color="green-2"
                    class="text-green text-weight-regular q-pa-xs q-px-sm">
                    {{ mode == 'history' ? service.state : service.name }}
                </q-badge>
            </div>
        </q-card-section>

        <q-card-section class="q-pt-none" v-if="mode!='selection'">
            <q-input
                stack-label
                label-color="primary"
                label="Observacion"
                class="q-mb-md"
                v-model="form.comment">
            </q-input>

            <q-list dense class="service-tracktime-list" v-if="mode=='history'">
                <q-item class="text-caption">
                    <q-item-section>Tomado</q-item-section>
                    <q-item-section>{{service.taken_at}}</q-item-section>
                </q-item>
                <q-item class="text-caption">
                    <q-item-section>Terminado</q-item-section>
                    <q-item-section>{{service.finished_at}}</q-item-section>
                </q-item>
                <q-item class="text-caption">
                    <q-item-section>Transcurrido</q-item-section>
                    <q-item-section>{{formatedElapsed}}</q-item-section>
                </q-item>
            </q-list>

            <div class="flex" v-else-if="mode!='selection'">
                <q-input
                    stack-label
                    label-color="primary"
                    label="Transcurrido"
                    :model-value="formatedElapsed">
                </q-input>
            </div>
        </q-card-section>

        <q-card-actions class="justify-end" v-if="mode=='taken'">
            <q-btn dense flat color="grey">Cancelar</q-btn>
            <q-btn
                flat
                color="primary"
                @click="finishTask('success')"
                :loading="form.progress">
                Aprobar
            </q-btn>
        </q-card-actions>
    </q-card>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useQuasar, date, format } from 'quasar';
import { useForm } from '@inertiajs/vue3';

const $q = useQuasar()
const elapsed = ref(0)
const props = defineProps({
    mode: {
        type: String,
        default: 'selection'
    },
    service: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm({
    service_id: null,
    order_id: null,
    state: ''
})

const formatedElapsed = computed(() => {
    let hours = format.pad(Math.floor(elapsed.value / 3600), 2)
    let min = format.pad(Math.floor((elapsed.value % 3600) / 60), 2)
    let seconds = format.pad(Math.floor((elapsed.value % 3600) % 60), 2)

    return `${hours}:${min}:${seconds}`
})

function finishTask(state) {
    form.state = state
    form.put('/order_services/update', {
        onSuccess: () => $q.notify('Service finalizado con éxito')
    })
}

function propToForm() {
    form.defaults({
        order_id: props.service.order_id,
        service_id: props.service.id,
        state: props.state,
        comment: props.service.comment
    })
    form.reset()
}

function nowAsUTC(){
    var date    = new Date();
    var now_utc = new Date(
                    date.getUTCFullYear(), date.getUTCMonth(),
                    date.getUTCDate(), date.getUTCHours(),
                    date.getUTCMinutes(), date.getUTCSeconds());
    return now_utc;
}

onMounted(() => {

    let takenAt = new Date(props.service.taken_at)
    let finishedAt = props.service.finished_at
        ? new Date(props.service.finished_at)
        : nowAsUTC()

    elapsed.value = date.getDateDiff(finishedAt, takenAt, 'seconds')
    propToForm()
    if (props.mode == 'taken') setInterval(() => elapsed.value = elapsed.value + 1, 1000)
})
</script>

<style lang="scss">
.service-tracktime-list {
    .q-item {
        padding-left: 0;
        padding-right: 0;
        &__section:last-child {
            text-align: right;
        }
    }
}
</style>
