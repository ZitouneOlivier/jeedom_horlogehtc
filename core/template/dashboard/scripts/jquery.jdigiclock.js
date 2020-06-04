/*
 * jDigiClock plugin 2.1
 *
 * http://www.radoslavdimov.com/jquery-plugins/jquery-plugin-digiclock/
 *
 * Copyright (c) 2009 Radoslav Dimov
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

(function ($) {
    $.fn.extend({
        jdigiclock: function (options) {
            var defaults = {
                clockImagesPath: 'plugins/horlogehtc/core/template/dashboard/images/clock/',
                weatherImagesPath: 'plugins/horlogehtc/core/template/dashboard/images/weather/',
                lang: 'fr',
                am_pm: false,
                weatherMetric: 'C',
                weatherUpdate: 60
            };

            var regional = [];
            regional['fr'] = {
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
            }


            var options = $.extend(defaults, options);

            return this.each(function () {

                var $this = $(this);
                var o = options;
                $this.clockImagesPath = o.clockImagesPath;
                $this.weatherImagesPath = o.weatherImagesPath;
                $this.lang = regional[o.lang] == undefined ? regional['fr'] : regional[o.lang];
                $this.am_pm = o.am_pm;
                $this.weatherMetric = o.weatherMetric == 'C' ? 1 : 0;
                $this.weatherUpdate = o.weatherUpdate;
                $this.currDate = '';
                $this.timeUpdate = '';
                $this.displayClock($this);
                $this.displayWeather($this);

                var largeur = $this.parents('div').width();
                largeur = parseFloat(largeur) + 30;


                var next = function () {
                    $this.find("#digital_container").animate({ "left": "+=" + largeur + "px" }, 400, function () {
                        $this.find("#forecast_container").animate({ "left": "0px" }, "400");
                        $this.find('#right_arrow').hide();
                        $this.find('#left_arrow').show();
                    });
                };
                $this.find('#right_arrow').bind('click', next);


                var prev = function () {
                    $this.find("#forecast_container").animate({ "left": "+=" + largeur + "px" }, 400, function () {
                        $this.find("#digital_container").animate({ "left": "30px" }, "400");
                        $this.find('#left_arrow').hide();
                        $this.find('#right_arrow').show();
                    });
                };
                $this.find('#left_arrow').bind('click', prev);

            });
        }
    });

    HtcDelay = function () {
        var delay = (60 - new Date().getSeconds()) * 1000;
        console.log('delay:' + delay)
        return delay;
    }

    $.fn.displayClock = function (el) {
        $.fn.getTime(el);
        setTimeout(function () { $.fn.displayClock(el) }, HtcDelay());
    }

    $.fn.displayWeather = function (el) {
        $.fn.getWeather(el);
        if (el.weatherUpdate > 0) {
            setTimeout(function () { $.fn.displayWeather(el) }, (el.weatherUpdate * 60 * 1000));
        }
    }

    $.fn.getTime = function (el) {
        var now = new Date();
        var old = new Date();
        old.setTime(now.getTime() - 60000);

        var now_hours, now_minutes, old_hours, old_minutes, timeOld = '';
        now_hours = now.getHours();
        now_minutes = now.getMinutes();
        old_hours = old.getHours();
        old_minutes = old.getMinutes();

        if (el.am_pm) {
            var am_pm = now_hours > 11 ? 'pm' : 'am';
            now_hours = ((now_hours > 12) ? now_hours - 12 : now_hours);
            old_hours = ((old_hours > 12) ? old_hours - 12 : old_hours);
        }

        now_hours = ((now_hours < 10) ? "0" : "") + now_hours;
        now_minutes = ((now_minutes < 10) ? "0" : "") + now_minutes;
        old_hours = ((old_hours < 10) ? "0" : "") + old_hours;
        old_minutes = ((old_minutes < 10) ? "0" : "") + old_minutes;
        // date
        el.currDate = el.lang.dayNames[now.getDay()] + '&nbsp;' + now.getDate() + '&nbsp;' + el.lang.monthNames[now.getMonth()];
        // time update
        el.timeUpdate = el.currDate + ',&nbsp;' + now_hours + ':' + now_minutes;

        var firstHourDigit = old_hours.substr(0, 1);
        var secondHourDigit = old_hours.substr(1, 1);
        var firstMinuteDigit = old_minutes.substr(0, 1);
        var secondMinuteDigit = old_minutes.substr(1, 1);

        timeOld += '<div id="hours"><div class="line"></div>';
        timeOld += '<div id="hours_bg"><img src="' + el.clockImagesPath + 'clockbg1.png" /></div>';
        timeOld += '<img src="' + el.clockImagesPath + firstHourDigit + '.png" id="fhd" class="first_digit" />';
        timeOld += '<img src="' + el.clockImagesPath + secondHourDigit + '.png" id="shd" class="second_digit" />';
        timeOld += '</div>';
        timeOld += '<div id="minutes"><div class="line"></div>';
        if (el.am_pm) {
            timeOld += '<div id="am_pm"><img src="' + el.clockImagesPath + am_pm + '.png" /></div>';
        }
        timeOld += '<div id="minutes_bg"><img src="' + el.clockImagesPath + 'clockbg1.png" /></div>';
        timeOld += '<img src="' + el.clockImagesPath + firstMinuteDigit + '.png" id="fmd" class="first_digit" />';
        timeOld += '<img src="' + el.clockImagesPath + secondMinuteDigit + '.png" id="smd" class="second_digit" />';
        timeOld += '</div>';

        el.find('#clock').html(timeOld);

        // set minutes
        if (secondMinuteDigit != '9') {
            firstMinuteDigit = firstMinuteDigit + '1';
        }

        if (old_minutes == '59') {
            firstMinuteDigit = '511';
        }

        setTimeout(function () {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-1.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
        }, 200);
        setTimeout(function () { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg3.png') }, 250);
        setTimeout(function () {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-2.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
        }, 400);
        setTimeout(function () { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg5.png') }, 450);
        setTimeout(function () {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-3.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
        }, 600);

        setTimeout(function () {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-1.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
        }, 200);
        setTimeout(function () { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg3.png') }, 250);
        setTimeout(function () {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-2.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
        }, 400);
        setTimeout(function () { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg5.png') }, 450);
        setTimeout(function () {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-3.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
        }, 600);

        setTimeout(function () { $('#fmd').attr('src', el.clockImagesPath + now_minutes.substr(0, 1) + '.png') }, 800);
        setTimeout(function () { $('#smd').attr('src', el.clockImagesPath + now_minutes.substr(1, 1) + '.png') }, 800);
        setTimeout(function () { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg1.png') }, 850);

        // set hours
        if (now_minutes == '00') {

            if (el.am_pm) {
                if (now_hours == '00') {
                    firstHourDigit = firstHourDigit + '1';
                    now_hours = '12';
                } else if (now_hours == '01') {
                    firstHourDigit = '001';
                    secondHourDigit = '111';
                } else {
                    firstHourDigit = firstHourDigit + '1';
                }
            } else {
                if (now_hours != '10') {
                    firstHourDigit = firstHourDigit + '1';
                }

                if (now_hours == '20') {
                    firstHourDigit = '1';
                }

                if (now_hours == '00') {
                    firstHourDigit = firstHourDigit + '1';
                    secondHourDigit = secondHourDigit + '11';
                }
            }

            setTimeout(function () {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-1.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
            }, 200);
            setTimeout(function () { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg3.png') }, 250);
            setTimeout(function () {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-2.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
            }, 400);
            setTimeout(function () { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg5.png') }, 450);
            setTimeout(function () {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-3.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
            }, 600);

            setTimeout(function () {
                $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-1.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
            }, 200);
            setTimeout(function () { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg3.png') }, 250);
            setTimeout(function () {
                $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-2.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
            }, 400);
            setTimeout(function () { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg5.png') }, 450);
            setTimeout(function () {
                $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-3.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
            }, 600);

            setTimeout(function () { $('#fhd').attr('src', el.clockImagesPath + now_hours.substr(0, 1) + '.png') }, 800);
            setTimeout(function () { $('#shd').attr('src', el.clockImagesPath + now_hours.substr(1, 1) + '.png') }, 800);
            setTimeout(function () { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg1.png') }, 850);
        }
    }

    $.fn.getWeather = function (el) {

        el.find('#date').html(el.currDate);
        el.find('#JourForecast').html(el.currDate);

    }

})(jQuery);