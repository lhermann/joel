/*
 * Mediaitem Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "MediaitemComponent",
    template: "#mediaitem-component",
    props: ["item"],
    data() {
        return {};
    },
    computed: {
        isRecording() {
            switch (this.item.type) {
                case "video":
                case "audio":
                    return true;
                case "series":
                case "speakers":
                case "topics":
                default:
                    return false;
            }
        },
        title() {
            if (this.isRecording) return this.item.title.rendered;
            return this.item.name;
        },
        length() {
            if (this.isRecording) return this.item.length;
            return this.item.count;
        },
        subtopics() {
            if (typeof this.item.subtopics_count === "undefined") return null;
            switch (this.item.subtopics_count) {
                case 0:
                    return `Keine Unterthemen`;
                case 1:
                    return `Ein Unterthema`;
                default:
                    return `${this.item.subtopics_count} Unterthemen`;
            }
        }
    },
    methods: {}
};
