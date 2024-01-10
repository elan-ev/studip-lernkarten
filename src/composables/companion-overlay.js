import { ref } from 'vue';

const show = ref(false);
const message = ref('');
const mood = ref('default');

export function useCompanionOverlay() {
    let timeout = null;

    function hideCompanionOverlay() {
        message.value = '';
        show.value = false;
    }

    function showCompanionOverlay(msg) {
        message.value = msg;
        show.value = true;
        if (timeout) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(hideCompanionOverlay, 4000);
    }

    return {
        message,
        mood,
        show,
        hideCompanionOverlay,
        showCompanionOverlay,
    };
}
