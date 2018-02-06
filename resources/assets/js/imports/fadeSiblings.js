/**
 * Add class .is-faded to all Siblings during hover
 *
 */

import $ from 'cash-dom';
// import $ from 'jquery-slim';

$(".jsFadeSiblings").on("mouseenter", function(e){
    $(this).siblings().addClass("is-faded");
}).on("mouseleave", function(e){
    $(this).siblings().removeClass("is-faded");
});
