import $ from "cash-dom";
import Popper from "popper.js";

$(document).ready(function() {
    $(".jsDropdown").each(function(dropdown, i) {
        let btn = $(dropdown)
                .parent()
                .find(".jsDropdownBtn")
                .get(0),
            placement = $(dropdown).attr("data-placement"),
            visible = false;

        let popper = new Popper(btn, dropdown, {
            placement: placement ? placement : "bottom-start",
            eventsEnabled: true
        });

        $(btn).on("click", function(event) {
            if (visible)
                visible = changeDropdownState("collapse", dropdown, btn);
            else visible = changeDropdownState("expand", dropdown, btn);

            // update again on click since the first update might have happened
            // when the dropdown was not rendered yet
            popper.scheduleUpdate();
        });

        // Stop event propagation from inside the dropdown
        // (so the next event listener works as described)
        $(dropdown).on("click", function(event) {
            event.stopPropagation();
        });

        // Collapse the dropdown if click event happens not on btn or dropdown
        $(document).on("click", function(event) {
            if (!event.target.isSameNode(btn)) {
                visible = changeDropdownState("collapse", dropdown, btn);
            }
        });
    });

    function changeDropdownState(state, dropdown, btn) {
        switch (state) {
            case "expand":
            default:
                $(dropdown).removeClass("u-hidden");
                $(btn).attr("aria-expanded", "true");
                return true;
            case "collapse":
                $(dropdown).addClass("u-hidden");
                $(btn).attr("aria-expanded", "false");
                return false;
        }
    }
});
