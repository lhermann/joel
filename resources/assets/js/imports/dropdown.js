
import $ from 'cash-dom';
import Popper from 'popper.js';

$(document).ready(function(){

    $(".jsDropdown").each( function(dropdown, i) {

        var btn = $(dropdown).parent().find(".jsDropdownBtn").get(0),
            placement = $(dropdown).attr("data-placement");

        var popper = new Popper(
            btn,
            dropdown,
            {
                placement: (placement ? placement : 'bottom-start')
            }
        );

        $(btn).on("click", function(){

            $(dropdown).toggleClass("u-hidden");

            // update again on click since the first update might have happened
            // when the dropdown was not rendered yet
            popper.update();

            if( $(this).attr("aria-expanded") == "false" ) {
                $(this).attr("aria-expanded", "true");
            } else {
                $(this).attr("aria-expanded", "false");
            }

        });

    });

});
