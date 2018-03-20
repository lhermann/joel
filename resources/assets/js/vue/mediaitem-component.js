/*
 * Mediaitem Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "MediaitemComponent",
    template: "#mediaitem-component",
    props: ["item"],
    data: function() {
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
                case "topcis":
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
        }
    },
    methods: {}
};
