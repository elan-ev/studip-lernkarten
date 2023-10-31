import { computed } from 'vue';
import { defineStore } from 'pinia';

export const useContextStore = defineStore(
    'context',
    () => {
        const isCourse = computed(() => {
            return 'cid' in window.STUDIP.URLHelper.parameters;
        });

        const id = computed(() => {
            return isCourse.value ? window.STUDIP.URLHelper.parameters.cid : window.STUDIP.USER_ID;
        });

        return { id, isCourse };
    },
    {
        persist: true
    }
);
