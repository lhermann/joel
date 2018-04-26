/*
 * Pagination Vue Component
 * @author: Lukas Hermann
 */

export default {
    name: "PaginationComponent",
    template: "#pagination-component",
    props: [
        "total",
        "perPage",
        "totalPages",
        "currentPage",
        "verbosity",
        "isLoading"
    ],
    data() {
        var range = 11;
        return {
            range: range,
            rangeCentre: Math.ceil(range / 2),
            rangeOffset: Math.floor(range / 2)
        };
    },
    computed: {
        verbose() {
            return this.verbosity === "verbose";
        },
        minimal() {
            return this.verbosity === "minimal";
        },
        buttons() {
            if (this.totalPages <= this.range) {
                return this.totalPages;
            } else {
                var arr = [1];
                for (var i = 1; i <= this.range - 2; i++) {
                    arr.push(this.rangeCentre - this.rangeOffset + i);
                }
                if (arr[1] > 2) arr[1] = "left";
                if (arr[this.range - 2] < this.totalPages - 1)
                    arr[this.range - 2] = "right";
                arr.push(this.totalPages);
                return arr;
            }
        },
        pageRangeDisplay() {
            let firstOfRange = 1 + (this.currentPage - 1) * this.perPage;
            let lastOfRange =
                this.total < this.perPage
                    ? this.total
                    : this.currentPage * this.perPage;
            return `${firstOfRange} - ${lastOfRange} von ${this.total}`;
        }
    },
    methods: {
        toPage(n) {
            document.activeElement.blur();
            if (n !== this.currentPage) this.$emit("to-page", n);
        },
        nextPage() {
            this.toPage(this.currentPage + 1);
        },
        previousPage() {
            this.toPage(this.currentPage - 1);
        },
        changeRange(direction) {
            document.activeElement.blur();
            if (direction === "left") {
                this.rangeCentre -= this.range - 4;
            } else {
                this.rangeCentre += this.range - 4;
            }
        }
    },
    watch: {
        // whenever question changes, this function will run
        currentPage(newValue, oldValue) {
            var offset = this.rangeOffset;
            if (newValue <= offset + 1) {
                this.rangeCentre = offset + 1;
            } else if (newValue > this.totalPages - offset) {
                this.rangeCentre = this.totalPages - offset;
            } else {
                this.rangeCentre = newValue;
            }
        }
    }
};
