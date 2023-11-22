<script setup>
import { ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import StudipIcon from './base/StudipIcon.vue';

const router = useRouter();

const query = ref('');

const onSubmit = () => {
    router.push({ name: 'search', query: { q: query.value } });
};
</script>

<template>
    <div id="sidebar-actions" class="sidebar-widget">
        <div class="sidebar-widget-header">{{ $gettext('Aktionen') }}</div>
        <div class="sidebar-widget-content">
            <form method="post">
                <ul class="widget-list widget-links" :aria-label="$gettext('Aktionen')">
                    <li class="!tw-pl-0">
                        <RouterLink :to="{ name: 'decks-create' }" as="button">
                            <StudipIcon shape="add" class="!tw-align-middle" />
                            {{ $gettext('Neuen Kartensatz erstellen') }}
                        </RouterLink>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</template>
