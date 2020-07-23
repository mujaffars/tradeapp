
(function () {


    $(function () {

        $('.tabbable').tab('show');
        $('.tabbable').on('show', function (e) {
            window.location.hash = e.target.hash;
        })

        if (window.location.hash.length > 0)
            $('a[href="' + window.location.hash + '"]').click();

        $('.dropdown-toggle').dropdown();
        $(".scrutinytriggers span").popover({
            'html': true,
            'trigger': 'hover',
            'placement': function (e) {
                if ($(this).attr('$element').offset().left < 150)
                    return 'right';
                return 'top';
            }
        });

        /**
         * Code added for opening notifications popup on dashboard
         * 
         * @author Mujaffar added on 09 Aug 2012
         */
        $(".scrutinytrigg span").popover({
            'html': true,
            'trigger': 'hover',
            'placement': function (e) {
                if ($(this).attr('$element').offset().left < 150)
                    return 'right';
                return 'top';
            }
        });

        //$("a.pophandle").popover({
        //    'title':'Rig Updates',
        //    'content': function(a){ return $( " " + $(this).attr('href') ).text() }
        //})

        function equalizeTabHeight() {
            var height = 0;
            $('.rigsection').each(function () {
                $(this).css('height', 'auto');
                height = Math.max(height, $(this).height())
            });
            $('.rigsection').height(height);
        }

        equalizeTabHeight();

        $(window).resize(equalizeTabHeight);




        function initStateNav() {
            $('.progressionnav a[href]').click(function () {
                var ajaxurl = $(this).attr('href').replace(/overview/, 'drillstate');
                $("#drillprogression").css("min-height", 400).load(ajaxurl, initStateNav);
                return false;
            });
        }

        initStateNav()

        $(".clsTooltip").popover({
            'html': true,
            'trigger': 'hover',
            'placement': function (e) {
                if ($(this).attr('$element').offset().top < 250)
                    return 'bottom';
                return 'top';
            }
        });

        $(".fisTooltip").popover({
            'html': true,
            'placement': 'top'
        });
        $(".fisTooltip").popover('show');

        var $scrollTarget = $('.clsDivReports');
        $scrollTarget.animate({
            scrollTop: $scrollTarget.height()
        }, 1000);

        $(".clsIconWea").dblclick("click", function () {
            window.open("http://forecast.io/#/f/" + $(this).attr('lat') + "," + $(this).attr('long'));
        });

        
    })

    /**
     * Click event code for submenu functionality
     * 
     * @author  Mujaffar Sanadi     Added on 13 Aug 2013
     */
    $(".subMenu").click(function () {
        $(this).parent().find('.dropdown-menu').toggle();
        return false;
    });

})()





function accumalitiveChart(data_series, targetid, title, subtitle, options) {
    // options = options || {};
    var opts = $.extend({}, {
        chart: {
            renderTo: targetid,
            defaultSeriesType: 'area'
        },
        title: {
            text: null
        },
        subtitle: {
            text: subtitle
        },
        xAxis: {
            labels: {
                formatter: function () {
                    return this.value; // clean, unformatted number for year
                }
            },
            allowDecimals: false
        },
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        tooltip: {
            formatter: function () {
                return this.series.name + ':' +
                        Highcharts.numberFormat(this.y, 0) + " on day " + this.x;
            }
        },
        plotOptions: {
            area: {
                pointStart: 1,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: data_series

    }, options)

    var chart = new Highcharts.Chart(opts);

    return chart;
}



function sddTotalChart(data_series, targetid, title, subtitle, options) {


    var opts = $.extend({}, {
        chart: {
            renderTo: targetid
        },
        title: {
            text: title
        },
        plotOptions: {
            column: {
                pointPadding: 0.1,
                borderWidth: 0,
                pointStart: 1
                        // pointWidth:5
            },
            line: {
                pointStart: 1
            }
        },
        xAxis: {
            labels: {
                formatter: function () {
                    return this.value; // clean, unformatted number for year
                }
            },
            allowDecimals: false
        },
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        tooltip: {
            formatter: function () {
                return this.series.name + ':' +
                        Highcharts.numberFormat(this.y, 0) + " on day " + this.x;
            }
        },
        series: data_series
    }, options)

    var chart = new Highcharts.Chart(opts)

    return chart;
}
$(document).keyup(function (e) {
    if (e.keyCode == 27) {
        $('.close').trigger('click');
    }   // esc
});

function switchLogin() {
    var fdata = {
        'sltUser': $("input:radio[name=sltUser]:checked").val()
    }
    $.ajax({
        url: '/users/switch-login',
        type: 'POST',
        data: fdata,
        dataType: 'html',
        async: false,
        error: function () {
        },
        success: function (resp) {
            window.setTimeout('location.reload()', 0);
        }
    });
}

function unsetSwitchLogin() {
    $.ajax({
        url: '/users/unset-switch-login',
        type: 'POST',
        dataType: 'html',
        async: false,
        error: function () {
        },
        success: function (resp) {
            window.setTimeout('location.reload()', 0);
        }
    });
}

function closeModal2(elem) {
    $(elem).parents("#ModalShell").modal('hide')
}

function closeAllModal() {
    $('.modal').modal('hide');
}

// Function to get url parameters
function getParameterByName(name, href)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(href);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

function drillPhases(phasetype) {
    switch (phasetype) {
        case 'mobilization':
        case 'Mobilization':
        case 'mobe':
        case 'Mobe':
            return 'RigUp';
            break;
        default :
            return phasetype;
            break;
    }
}