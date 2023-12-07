import { ref, computed } from 'vue';
import { defineStore } from 'pinia';
import { api } from '../api.js';
import { useContextStore } from './context.js';

export const useCourseMembershipsStore = defineStore(
    'courseMemberships',
    () => {
        const records = ref(new Map());
        const isLoading = ref(false);
        const errors = ref(false);

        function storeRecord(newRecord) {
            records.value.set(newRecord.id, newRecord);
        }

        const all = computed(() => {
            return [...records.value.values()];
        });

        const userId = computed(() => window.STUDIP.USER_ID);

        async function fetch() {
            isLoading.value = true;

            try {
                const { data } = await api.fetch(`users/${userId.value}/course-memberships`, {
                    params: { include: 'course' },
                });
                data.forEach(storeRecord);
            } catch (errors) {
                console.error('fetching course-memberships', errors);
                errors.value = errors;
            }
            isLoading.value = false;
        }

        function byId(id) {
            return records.value.get(id);
        }

        return {
            all,
            byId,
            errors,
            fetch,
            isLoading,
        };
    },
    {
        persist: true,
    },
);
