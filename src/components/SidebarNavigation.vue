<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const matchedName = computed(() => route?.matched?.[0]?.name ?? '');
const unmatched = computed(() => !['home', 'folders', 'folder'].includes(matchedName.value));
</script>
<template>
    <ul
        class="widget-list widget-links sidebar-navigation navigation-level-3"
        :aria-label="$gettext('Dritte Navigationsebene')"
    >
        <li :class="{ active: matchedName === 'home' || unmatched }">
            <RouterLink
                to="/"
                id="nav_lernkarten_index"
                activeClass="active"
                ariaCurrentValue="page"
            >
                {{ $gettext('Lernkarten') }}
            </RouterLink>
        </li>
        <li :class="{ active: ['folder', 'folders'].includes(matchedName) }">
            <RouterLink
                to="/folders"
                id="nav_lernkarten_folders"
                activeClass="active"
                ariaCurrentValue="page"
            >
                {{ $gettext('Ordnerverwaltung') }}
            </RouterLink>
        </li>
    </ul>
</template>
