<script setup>
import { onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import Sidebar from './components/Sidebar.vue';

onMounted(hideOriginalSidebarNavigation);

function hideOriginalSidebarNavigation() {
    const navigation = document.querySelector('#sidebar-navigation form');
    navigation.querySelector(
        '.sidebar-navigation:not(.lernkarten-sidebar-navigation)'
    ).hidden = true;
}
</script>

<template>
    <header>
        <div class="wrapper">
            <nav>
                <RouterLink to="/">Home</RouterLink>
            </nav>
        </div>
    </header>

    <RouterView />

    <Teleport to="#sidebar-navigation form">
        <Sidebar />
    </Teleport>
</template>
