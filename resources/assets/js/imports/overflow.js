/**
 * Overflow controlls horizontal content that is cut off because of the width of
 * the screen and provides controlls for it
 */

import $ from 'cash-dom';
// import $ from 'jquery-slim';


/*
 * Initiate variables
 * 1. Width of one of the nav buttons
 */
var overflowNavWidth = 42, /* 1 */
    overflowArray = [];

/*
 * Class for .o-overflow objects
 */
function Overflow(container) {
    this.container     = container;
    this.content       = $(container).find(".o-overflow__content").get(0);
    this.count         = $(this.content).children().length;
    this.childrenWidth = parseInt($(this.content).children().first().width());
    this.spaceBetween  = parseInt($(this.content).data("space-between"));
    this.innerWidth    = parseInt(this.count * this.childrenWidth + this.spaceBetween);
    this.outerWidth    = parseInt($(this.container).width());
    this.currentPos    = 0;
    this.state         = "is-left";
    // console.log( "this.childrenWidth " + this.childrenWidth );
    // console.log( "this.spaceBetween " + this.spaceBetween );
    // console.log( "this.innerWidth " + this.innerWidth );
    // console.log( "this.outerWidth " + this.outerWidth );

    this.getShiftWidth = function(direction) {
        var shiftCount = Math.round( (this.outerWidth - overflowNavWidth) / this.childrenWidth ),
            shiftWidth = this.childrenWidth * shiftCount;

        switch (direction) {
          case "left":
            if( this.currentPos - shiftWidth >= 0 ) {
                return shiftWidth;
            } else {
                return this.currentPos;
            }
            break;
          case "right":
          default:
            if( this.currentPos + shiftWidth + this.outerWidth <= this.innerWidth ) {
                return shiftWidth;
            } else {
                return this.innerWidth - this.outerWidth - this.currentPos;
            }
            break;
        }
    };

    this.shiftRight = function() {
        var shift = this.getShiftWidth("right");
        this.currentPos += shift
        this.setContentX( this.currentPos );
        this.updateContainerState();
    };

    this.shiftLeft = function() {
        var shift = this.getShiftWidth("left");
        this.currentPos -= shift;
        this.setContentX( this.currentPos );
        this.updateContainerState();
    }

    this.setContentX = function(x) {
        $(this.content).css( "transform", "translateX(-" + x + "px)" );
    };

    /*
     * Add a little bit of wiggle room
     */
    this.updateContainerState = function() {
        $(this.container).removeClass( this.state );
        if( this.currentPos <= 1 ) { /* 1 */
            $(this.container).addClass( "is-left" );
            this.state = "is-left";
        } else if( this.currentPos >= this.innerWidth - this.outerWidth ) {
            $(this.container).addClass( "is-right" );
            this.state = "is-right";
        } else {
            $(this.container).addClass( "is-middle" );
            this.state = "is-middle";
        }
    };

    this.updateContainerWidth = function() {
        if( this.currentPos > this.innerWidth - this.outerWidth ) {
            this.currentPos = this.innerWidth - this.outerWidth;
            this.setContentX( this.currentPos );
        }
        this.updateContainerState();
    };

    this.updateContainerWidth();
}

/*
 * Initiate objects for overflow elements
 */
$(".o-overflow").each( function(element, i) {

    overflowArray.push( new Overflow(element) );

    $(overflowArray[i].container).find(".jsNavLeft").on( "click", function(event){
        overflowArray[i].shiftLeft();
    });

    $(overflowArray[i].container).find(".jsNavRight").on( "click", function(event){
        overflowArray[i].shiftRight();
    });

});

/*
 * Adapt responsively to viewport changes
 */
$( window ).on("resize", function() {
    overflowArray.forEach( function(overflow) {
        overflow.updateContainerWidth();
    })
});
