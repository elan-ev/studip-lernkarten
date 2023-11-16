<script setup>
import { computed } from 'vue';
import StudipIcon from '../components/base/StudipIcon.vue';

const props = defineProps(['cards', 'index']);

const cardsLeft = computed(() => props.cards.length - (props.index + 1));
const progress = computed(() =>
    toPercent(props.cards.length ? props.index / props.cards.length : 0)
);

function toPercent(num) {
    return Math.floor(num * 100);
}
</script>

<template>
    <div class="">
        <div class="tw-flex tw-gap-2 tw-justify-between tw-items-baseline tw-text-sm">
            <span>
                <span class="">
                    <StudipIcon class="tw-align-middle tw-mr-1" shape="dialog-cards" role="info" />
                    <span>Alle Karten</span>
                </span>
                –
                <span> Karte {{ index + 1 }} von {{ cards.length }} </span>
                –
                <span> Fortschritt: {{ progress }}% </span>
            </span>
            <span>
                Verbleibende Karten:
                <span class="tw-text-lg">{{ cardsLeft }}</span>
            </span>
        </div>
        <div class="tw-h-[1px] tw-bg-slate-300 tw-w-full" />
    </div>
</template>
