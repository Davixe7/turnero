<template>
    <q-layout :view="'hhh LpR lff'">
        <q-header>
            <q-toolbar>
                <q-btn flat round icon="menu" @click="leftDrawer=!leftDrawer"></q-btn>
                <q-toolbar-title>Turnero</q-toolbar-title>

                <div class="q-px-md">
                    {{ auth ? auth?.user?.name : ''}}
                </div>
                <q-btn flat round icon="logout" @click="logout"></q-btn>
            </q-toolbar>
        </q-header>

        <q-drawer v-model="leftDrawer" bordered>
            <q-list>
                <q-item
                    class="text-grey-7"
                    v-for="route in routes"
                    :active="router.page.url.includes(route.name)"
                    :active-class="'admin-menu-link'"
                    @click="router.visit(route.name)"
                    clickable
                    v-ripple>
                    <q-item-section avatar>
                        <q-icon :name="route.icon"></q-icon>
                    </q-item-section>
                    <q-item-section>
                        {{route.label}}
                    </q-item-section>
                </q-item>
            </q-list>
        </q-drawer>

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
    { name: '/form_fields/create', icon: 'description', label: 'Formulario'},
    { name: '/messages', icon: 'message', label: 'Mensajería'},
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
