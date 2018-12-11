/* ==========================================================================
   JOEL WORDPRESS THEME ADMIN
   ========================================================================== */

import "./admin-vue/recording-status.js";

/*
 * Changed the following in chartist.js for this to work:
 *    // if (typeof define === 'function' && define.amd) {
 *    if (root && typeof define === 'function' && define.amd) {
 */
import Chartist from "chartist";
if (document.getElementById("trac-download-chart")) {
    new Chartist.Line("#trac-download-chart", window.chartist_data);
}

/*
 * LEGACY
 */
(function($) {
    /*
     * This function will toggle the video-alert message.
     * When a new video is selected the green message is shown.
     * When a video exists already and a new one is selected, the red message is shown.
     */
    // Video Info Messages
    $("[data-name='recording_select']").change(event => {
        let value = event.target.value;
        // console.log({ event, value });
        VideoExistAlert(Boolean(value));
        $('div[id^="format-info-"]').addClass("hidden");
        $(`div[data-value="${value}"]`).removeClass("hidden");
    });
    // FTP Server Credentials
    $("#ftp-credentials-button").click(function() {
        $("#ftp-credentials").toggleClass("alert-invisible");
    });

    // function to show/hide the Message
    function VideoExistAlert(selected) {
        var fileExists = $("#videofile-exists").data("bool");
        $("#alert-exists").addClass("hidden");
        $("#alert-overwritten").addClass("hidden");
        if (selected && fileExists) {
            $("#alert-overwritten").removeClass("hidden");
        } else if (fileExists) {
            $("#alert-exists").removeClass("hidden");
        }
    }

    VideoExistAlert(false);
})(jQuery);
