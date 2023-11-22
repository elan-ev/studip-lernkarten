<script setup>
import { onMounted } from 'vue';
import { RouterView } from 'vue-router';
import SidebarNavigation from './components/SidebarNavigation.vue';
import SidebarSearch from './components/SidebarSearch.vue';

hideOriginalSidebarNavigation();

function hideOriginalSidebarNavigation() {
    const navigation = document.querySelector('#sidebar-navigation form');
    navigation.querySelector(
        '.sidebar-navigation:not(.lernkarten-sidebar-navigation)'
    ).hidden = true;
}
</script>

<template>
    <RouterView />

    <Teleport to="#sidebar-navigation form">
        <SidebarNavigation />
    </Teleport>

    <Teleport to="#sidebar">
        <SidebarSearch />
    </Teleport>
</template>
