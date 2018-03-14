/*
 * Mediaitem Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "MediaitemComponent",
    template: "#mediaitem-component",
    props: ["item"],
    data: function() {
        return {
            type: "video"
        };
    },
    computed: {
        title() {
            return this.item.title.rendered;
        }
    },
    methods: {}
}
