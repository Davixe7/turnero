<template>
    <q-layout :view="'hhh LpR lff'">
        <q-header>
            <q-toolbar>
                <q-toolbar-title>Empleados</q-toolbar-title>

                <div class="q-px-md">
                    {{ auth ? auth?.user?.name : ''}}
                </div>
                <q-btn flat round icon="logout" @click="logout"></q-btn>
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page class="q-pa-lg">
                <slot></slot>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['auth'])

const routes = [
    { name: '/orders', icon: 'pending_actions', label: 'Ordenes'},
    { name: '/employees', icon: 'engineering', label: 'Empleados'},
    { name: '/services', icon: 'shopping_bag', label: 'Servicios'},
    { name: '/clients', icon: 'groups', label: 'Clientes'},
]
const leftDrawer = ref(false)
function logout(){
    router.post('/logout')
}
</script>

<style lang="scss">
.q-toolbar {
    min-height: 56px !important;
}
.q-drawer {
    width: 270px;
    padding: 8px 15px;
}
.q-item {
    border-radius: 5px;
    padding: 6px 15px;
}
.q-item.admin-menu-link {
    // color: rgb(49, 46, 63) !important;
    // background: rgba(81, 81, 255, 0.397);
    color: var(--q-primary) !important;
    background: #2557d840;
    font-weight: 500;
}
</style>
