import { computed } from 'vue';
import { defineStore } from 'pinia';

export const useContextStore = defineStore(
    'context',
    () => {
        const id = computed(() => {
            return isCourse.value ? window.STUDIP.URLHelper.parameters.cid : userId.value;
        });

        const isCourse = computed(() => {
            return 'cid' in window.STUDIP.URLHelper.parameters;
        });

        const type = computed(() => (isCourse.value ? 'courses' : 'users'));

        const userId = computed(() => window.STUDIP.USER_ID);

        return { id, isCourse, type, userId };
    },
    {
        persist: true,
    },
);
