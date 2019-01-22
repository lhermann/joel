/*
 * Mediaitem Vue Component
 * @author: Lukas Hermann
 */

import differenceInDays from "date-fns/differenceInDays";
import parseISO from "date-fns/parseISO";

export default {
    name: "MediaitemComponent",
    template: "#mediaitem-component",
    props: ["item"],
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
        isNew() {
            const date = parseISO(this.item.date_gmt + "Z");
            return differenceInDays(Date.now(), date) <= 7;
        },
        title() {
            const title = this.isRecording
                ? this.item.title.rendered
                : this.item.name;
            if (title.length < 90) {
                return title;
            } else {
                return title.replace(/^(.{0,90}[\s]).*$/, "$1...");
            }
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
        },
        isDummy() {
            return typeof this.item.dummy !== "undefined";
        }
    },
    methods: {}
};
