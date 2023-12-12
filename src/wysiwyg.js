export function loadWysiwyg() {
    return window.STUDIP.loadChunk('wysiwyg').then((ClassicEditor) => ({
        install: (app, options) => {
            app.provide('ClassicEditor', ClassicEditor);
        },
    }));
}
