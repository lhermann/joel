/*
 * Streamcheck Vue Component
 * @author: Lukas Hermann
 */

import axios from "axios";

export default {
    name: "Streamcheck",
    template: "#streamcheck-component",
    props: {
        stream: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            loading: true,
            live: false
        };
    },
    mounted() {
        axios
            .get(this.stream, {
                baseURL: "//streamcheck.joelmedia.de/"
            })
            .then(response => (this.live = response.data.live))
            .catch()
            .then(() => (this.loading = false));
    }
};
