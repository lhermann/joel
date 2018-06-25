<?php
use AppTheme\Store;
if( Store::isset_then_set('vue-media-sorting-component') ) return;
?>

<!-- template for the pagination component -->
<?= '<script type="text/x-template" id="sorting-component">' ?>

    <div>

        <span>Sortieren:</span>
        <button ref="reference"
            id="sorting-dropdown"
            class="c-btn c-btn--dropdown c-btn--tiny c-btn--secondary u-ml--"
            v-on:click="onButtonClick"
            aria-haspopup="true"
            :aria-expanded="show ? 'true' : 'false'"
        >
            {{ currentOption.label }}
        </button>

        <ul ref="popper"
            class="c-dropdown c-dropdown--round"
            :class="{'is-visible': fadeIn}"
            v-show="show"
            aria-labelledby="sorting"
        >
        <!-- v-show="show" -->
            <li v-for="option in options"
                class="c-dropdown__item"
                :class="{'is-active': isCurrent(option)}"
            >
                <button class="c-dropdown__link"
                    v-on:click="onSelectOption(option)"
                >
                    {{ option.label }}
                </button>
            </li>
        </ul>

    </div>

<?= '</script>' ?>

