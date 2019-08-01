<?php
use function Tonik\Theme\App\template;
use function Tonik\Theme\App\config;
$options = isset($options) ? str_replace('"', "'", json_encode($options)) : '{}';
$params = isset($params) ? str_replace('"', "'", json_encode($params)) : '{}';
?>

<!-- Vue livestream dropdown root component -->
<div id="<?= $id ?>"
    class="<?= $style_modifier ?>"
    data-vue="livestream-dropdown"
    :init="init(<?= $options ?>, <?= $params ?>)"
    @mouseover="onMouseover"
>

    <a href="<?= home_url( '/livestream/' ) ?>"
        class="c-link c-link--block c-link--primary c-primary-nav__link">
        <streamcheck :stream="options.stream">
            <template slot-scope="props">
                <span class="c-dot u-mr--"
                    :class="{'c-dot--green': props.live, 'is-loading': props.loading}">
                </span>
            </template>
        </streamcheck>
        <span class="u-hidden-until@desktop"><?= __('Livestream', config('textdomain')) ?></span>
        <span class="u-hidden-from@desktop"><?= _x('Live', 'Short for livestream', config('textdomain')) ?></span>
        <span class="u-ic-keyboard_arrow_down"></span>
    </a>

    <ul class="c-primary-nav__dropdown" style="width: 500px">

            <li v-cloak v-for="event in events" :key="event.occurrence_id"
                class="c-primary-nav__dropdown-item"
                :class="{'u-bg-muted': !event.today}">
                <component :is="event.today ? 'a' : 'div'"
                    class="o-box o-box--natural"
                    :class="{'c-link c-link--block c-link--primary': event.today}"
                    href="<?= home_url( '/livestream/' ) ?>">
                    <div class="o-flag">
                        <div v-if="event.thumbnail" class="o-flag__img u-mr"
                            v-html="event.thumbnail"></div>
                        <div class="o-flag__body">
                            <div>
                                <span v-if="event.today" class="c-badge"
                                    :class="{'c-badge--success': event.now, 'u-textmuted': !event.now}"
                                >live</span>
                                {{event.post_title}}
                            </div>
                            <div class="u-default">
                                <template v-if="event.today">
                                    <strong class="u-green"><?= __('Today', config('textdomain')) ?></strong> &middot;
                                </template>
                                <template v-if="event.tomorrow">
                                    <strong class="u-yellow"><?= __('Tomorrow', config('textdomain')) ?></strong> &middot;
                                </template>
                                <span
                                    :class="{'u-bold': !(event.today || event.tomorrow)}"
                                >{{weekday(event.StartDate)}}</span>,
                                {{date(event.StartDate)}}
                                <?= _x('at', 'as in "at 10 am"', config('textdomain')) ?>
                                {{time(event.StartDate + ' ' + event.StartTime)}}
                            </div>
                        </div>
                    </div>
                </component>
            </li>

            <li v-if="!events.length"
                class="c-primary-nav__dropdown-item u-bg-muted u-p">
                <div class="c-spinner u-box-center"></div>
            </li>

            <li class="c-primary-nav__dropdown-item">
                <a href="<?= home_url( '/livestream/' ) ?>"
                    class="c-link c-link--block c-link--primary u-truncate">
                    <?= __('Go to livestream', config('textdomain')) //Livestream &Ouml;ffnen ?>
                    <span class="u-ic-arrow_forward"></span>
                </a>
            </li>

    </ul>

</div>
