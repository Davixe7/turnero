<template>
    <q-layout :view="'hhh LpR lff'">
        <q-header>
            <q-toolbar class="bg-black">
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
                    :active="router.page.url == route.name"
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
const leftDrawer = ref(false)

const routes = [
    { name: '/root/users', icon: 'groups', label: 'Usuarios'},
]

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
    color: var(--q-primary) !important;
    background: #2557d840;
    font-weight: 500;
}
</style>
