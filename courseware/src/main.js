import { setActivePinia } from 'pinia';
import CoursewareBlock from './CoursewareBlock.vue';

window.STUDIP.eventBus.on('courseware:init-plugin-manager', (pluginManager) => {
    STUDIP.loadChunk('vue').then((exports) => {
        setActivePinia(exports.pinia);
        pluginManager.addBlock('courseware-lernkarten-block', CoursewareBlock);
    });
});

export default CoursewareBlock;
