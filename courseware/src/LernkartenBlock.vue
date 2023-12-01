<template>
    <div class="cw-block cw-block-lernkarten">
        <component
            :is="coursewarePluginComponents.CoursewareDefaultBlock"
            ref="defaultBlock"
            :block="block"
            :canEdit="canEdit"
            :isTeacher="isTeacher"
            :preview="true"
            :defaultGrade="false"
            @storeEdit="storeBlock"
            @closeEdit="initCurrentData"
        >
            <template #content>
                <translate v-if="!isBlockInitialized">
                    Die Lernkarten werden angezeigt, nachdem der Block gespeichert wurde.
                </translate>
                <div v-else>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enimad minim veniam, quis
                    nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                    culpa qui officia deserunt mollit anim id est laborum.
                    <lernkarten-block></lernkarten-block>
                </div>
            </template>
            <template v-if="canEdit" #edit>
                <component
                    :is="coursewarePluginComponents.CoursewareCollapsibleBox"
                    title="Grunddaten"
                    :open="true"
                >
                    <form class="default" @submit.prevent="">
                        <label>
                            <translate>Höhe</translate>
                            <input type="number" min="0" v-model.number="blockHeight" />
                        </label>
                    </form>
                </component>
            </template>
            <template #info><translate>Informationen zum Lernkartenblock</translate></template>
        </component>
    </div>
</template>

<script>
const get = window._.get.bind(window._);
import { mapActions, mapGetters } from 'vuex';

export default {
    components: {},
    name: 'courseware-lernkarten-block',
    props: {
        block: Object,
        canEdit: Boolean,
        isTeacher: Boolean,
    },
    data() {
        return {
            blockHeight: 0,
        };
    },
    computed: {
        ...mapGetters({
            context: 'context',
        }),
        isBlockInitialized: function () {
            return this.block.attributes.payload.initialized;
        },
    },
    methods: {
        ...mapActions({
            updateBlock: 'updateBlockInContainer',
        }),
        initCurrentData() {
            this.blockHeight = this.block.attributes.payload.height || 500;
        },
        storeBlock() {
            const attributes = {
                ...this.block.attributes,
                payload: {
                    ...this.block.attributes.payload,
                    initialized: true,
                    height: this.blockHeight,
                },
            };
            this.updateBlock({
                attributes,
                blockId: this.block.id,
                containerId: this.block.relationships.container.data.id,
            }).then(() => {
                // close the edit menu
                this.$refs.defaultBlock.displayFeature(false);
            });
        },
    },
    watch: {
        $props: {
            handler() {
                console.warn('props watcher', JSON.parse(JSON.stringify(this.$props)));
            },
            deep: true,
            immediate: true,
        },
    },
    async mounted() {
        this.initCurrentData();
        if (!this.block.attributes.payload.initialized) {
            this.storeBlock();
        }
    },
    inject: ['coursewarePluginComponents'],
};
</script>

<style></style>
