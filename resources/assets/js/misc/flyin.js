import $ from 'cash-dom';
// import $ from 'jquery-slim';

var siteWrapper = document.getElementById("siteWrapper"),
    allowScrolling = true;

/**
 * open and close mobile nav
 */
$(".jsFlyinBtn").on( "click", toggleFlyin );

function toggleFlyin( event ) {
    var target = $(this).attr('data-target');
    if( $(this).attr('data-action') == 'open' ) {
        $('#'+target).addClass('c-flyin--open');
        $(siteWrapper).addClass('c-site-wrapper--faded');
        allowScrolling = false;
    } else {
        $('#'+target).removeClass('c-flyin--open');
        $(siteWrapper).removeClass('c-site-wrapper--faded');
        allowScrolling = true;
    }
}

/**
 * Enable/Disable scrolling on #siteWrapper when mobile nav is open
 * on iPhone/iPad’s Safari
 */
if( siteWrapper ) {
    siteWrapper.ontouchmove = function (e) {
        if(allowScrolling) {
            return true; // Enable scrolling.
        } else {
            e.preventDefault(); // Disable scrolling.
        }
    }
}


