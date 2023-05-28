<template>
    <div id="users">
        <q-table
            title="Usuarios"
            :columns="columns"
            :rows="rows"
            :search="search">
            <template v-slot:body-cell-actions="props">
                <q-td class="text-grey flex justify-end">
                    <Link class="text-grey" :href="`/root/users/${props.row.id}/edit`">
                        <q-btn flat round size="12px" icon="edit">
                        </q-btn>
                    </Link>
                </q-td>
            </template>
        </q-table>

        <q-page-sticky position="bottom-right" :offset="[18, 18]">
            <Link href="/root/users/create">
                <q-btn fab icon="add"></q-btn>
            </Link>
        </q-page-sticky>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
const props = defineProps({users: Array})
const search = ref('')
const rows = ref([])
const columns = ref([
    {name:'name', field: 'name', label: 'Nombre', align: 'left'},
    {name:'email', field: 'email', label: 'Email', align: 'left'},
    {name:'actions', align: 'right'},
])
onMounted(() => rows.value = [...props.users])
</script>
