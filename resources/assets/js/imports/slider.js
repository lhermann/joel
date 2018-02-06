/*
 * Joel Media Slider
 * @author: Lukas Hermann
 */

import $ from 'cash-dom';
// import $ from 'jquery-slim';

/* Keep in sync with _components.slider.scss */
var sliders = [
    {
        slideDuration:   3000,
        slideTransition: 800,
        slideDelay:      200,
        automatic:       "initial",
        node:            $("#mainSlider"),
        object:          false
    },
    {
        slideDuration:   8000,
        slideTransition: 800,
        slideDelay:      200,
        automatic:       "always",
        node:            $("#quoteSlider"),
        object:          false
    }
]

var slideDuration   = 3000;
var slideTransition = 800;
var slideDelay      = 200;

var mainSlider = $("#mainSlider");
var quoteSlider = $("#quoteSlider");

function mod(n, m) {
    return ((n % m) + m) % m;
}

// holder class for each slide
function Slide(id, slide, nav, slideTransition) {

    this.id = id;
    this.slide = slide;
    this.nav = nav;
    this.class = "";
    this.zIndex = false;
    this.slideTransition = typeof slideTransition === 'number' ? slideTransition : 800;

    this.setZIndex = function(z) {
        $(this.slide).css( "z-index", z );
        this.zIndex = z;
    };

    this.activateNav = function() {
        $(this.nav).addClass('is-active');
    };

    this.deactivateNav = function() {
        $(this.nav).removeClass('is-active');
    };

    this.animateEnter = function() {
        this.add('is-entering');
        setTimeout(function(obj) {
            obj.rm('is-entering');
        }, slideTransition, this);
    };

    this.animateLeave = function() {
        this.add('is-leaving');
        setTimeout(function(obj) {
            obj.rm('is-leaving');
        }, slideTransition, this);
    };

    this.add = function(cssClass) {
        $(this.slide).addClass(cssClass);
    };

    this.rm = function(cssClass) {
        $(this.slide).removeClass(cssClass);
    };
}

// slider with its methods
function Slider(slider, slideDelay, slideTransition) {

    var sliderList = $(slider).find('.jsSliderList').children();
    var sliderNav = $(slider).find('.jsSliderNav').children();

    // populate slides array
    var slides = [];
    $(sliderList).each(function( value, index ) {
        slides.push( new Slide(
            index+1,
            this,
            $(sliderNav[index]).get(0),
            slideTransition
        ));
    })

    // bring slide array into initial order
    slides
        .reverse()
        .unshift(slides.pop());

    // Slide Stack
    this.slideStack = slides;
    this.slideCount = slides.length;
    this.currentSlide = 1;

    // slider
    this.slider = slider;
    this.numChanged = 0;
    this.isAutomatic = true;
    this.intervalId = 0;

    // timing
    this.slideDelay = typeof slideDelay === 'number' ? slideDelay : 200;

    // setup dom

    this.setZIndices = function() {
        var slideCount = this.slideCount;
        this.slideStack.forEach( function(slide, index) {
            slide.setZIndex( slideCount - index );
        });
    }
    this.setZIndices();

    this.setActiveNav = function() {
        this.slideStack.forEach( function(slide, index) {
            if( index == 0 ) {
                slide.activateNav();
            } else {
                slide.deactivateNav();
            }
        });
    }
    this.setActiveNav();

    this.transitionSlide = function(direction) {
        this.setZIndices();
        this.setActiveNav();

        if(direction == 'backwards') {
            (this.slideStack[ this.slideStack.length - 1 ]).animateLeave();
        } else {
            (this.slideStack[0]).animateEnter();
        }
    }

    /*
     * Interval Function handles one single slide transition
     */
    this.intervalFunction = function(obj, id) {
        console.log("currentSlide " + obj.currentSlide);
        if( obj.currentSlide == id ) {
            clearInterval(obj.intervalId);
            return true;
        }

        if( id > obj.currentSlide ) {
            obj.slideStack.unshift( obj.slideStack.pop() );
            obj.transitionSlide('forwards');
        } else {
            obj.slideStack.push( obj.slideStack.shift() );
            obj.transitionSlide('backwards');
        }

        console.log(obj.slideStack);
        obj.currentSlide = obj.slideStack[0].id;

        if( obj.currentSlide == id ) {
            clearInterval(obj.intervalId);
            return true;
        }
        return false;
    }

    /*
     * Change to slide with id
     */
    this.slide = function(id) {
        console.log("target id " + id);

        this.intervalFunction(this, id);
        this.intervalId = setInterval( this.intervalFunction, this.slideDelay, this, id);

        if( this.numChanged++ >= this.slideCount - 1 ) {
            this.deactivateAutomatic();
        }
    }

    /*
     * Change to next slide
     */
    this.nextSlide = function() {
        if( this.currentSlide == this.slideCount ) {
            this.slide( 1 )
        } else {
            this.slide( this.currentSlide + 1 )
        }
    }

    /*
     * Change to previous slide
     */
    this.previousSlide = function() {
        if( this.currentSlide == 1 ) {
            this.slide( this.slideCount )
        } else {
            this.slide( this.currentSlide - 1 )
        }
    }

    /*
     * Deactivate automatic changing of slides
     */
    this.deactivateAutomatic = function() {
        if( this.isAutomatic == true ) {
            $(this.slider).removeClass('is-automatic');
            this.isAutomatic = false;
        }
    }

}




$(document).ready( function() {

    $(".jsSliderNav a").on("click", function( event ) {
        event.preventDefault();
    });

    /*
     * A little timeout is necessary otherwise the animation classes will be
     * added before css is done parsing, thus not showing transition effects
     */
    setTimeout(function() {

        sliders.forEach(function(slider) {

            if( $(slider.node).length ) {

                slider.object = new Slider( slider.node, slider.slideDelay, slider.slideTransition );

                // attach event handler to buttons
                $(".jsSliderBtn").on( "click", function(event){
                    slider.object.deactivateAutomatic();
                    if( $(this).attr('data') == 'next' ) {
                        slider.object.nextSlide();
                    } else {
                        slider.object.previousSlide();
                    }
                });

                // attach event handler to slider navigation
                $(".jsSliderNav li").on( "click", function(event){
                    event.preventDefault();
                    slider.object.deactivateAutomatic();
                    slider.object.slide( $(this).attr('data') );
                })

                switch(slider.automatic) {
                    case "initial":
                        setInterval( function(){
                            if( slider.object.isAutomatic ) {
                                slider.object.nextSlide();
                            }
                        }, slider.slideDuration );
                        break;

                    case "always":
                        setInterval( function(){
                            slider.object.nextSlide();
                        }, slider.slideDuration );
                        break;
                }

            }

        });

    }, 300);

});
