<div id="<?= $id ?>" class="c-slider <?= $style_modifier ?> is-automatic">

    <div class="c-slider__control c-slider__control--left">
        <button class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--right c-slider__btn jsSliderBtn" data="previous">
            <span class="u-ic-keyboard_arrow_left"></span>
        </button>
    </div>

    <div class="c-slider__control c-slider__control--right">
        <button class="c-btn c-btn--dark c-btn--bigicon c-btn--square c-btn--left c-slider__btn jsSliderBtn" data="next">
            <span class="u-ic-keyboard_arrow_right"></span>
        </button>
    </div>

    <ul class="c-slider__nav jsSliderNav">

        {{# slider.slides }}

        <li id="{{slider.id}}Nav{{id}}" data="{{id}}">
            <a class="c-btn c-btn--dark c-slider__btn" href="#slide{{id}}">
                <div class="c-slider__btn__fill"></div>
            </a>
        </li>

        {{/ slider.slides }}

    </ul>

    <ul class="c-slider__list jsSliderList">

        {{# slider.slides }}

        <li id="{{slider.id}}Slide{{id}}" class="c-slider__item">

            {{# joelmedia }}
                {{> molecules-slide-joelmedia }}
            {{/ joelmedia }}
            {{^ joelmedia }}
                {{> molecules-slide }}
            {{/ joelmedia }}

        </li>

        {{/ slider.slides }}

    </ul>

</div>

