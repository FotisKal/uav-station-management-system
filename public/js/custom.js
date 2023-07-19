$(document).on("click","ul.nav li.parent > a ", function(){
    $(this).find('i').toggleClass("fa-minus");
});
$(".sidebar span.icon").find('em:first').addClass("fa-plus");

$('#calendar').datepicker({
		});

$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

$(document).ready(function(){
    setInterval(function(){
        opt_1 = 'fa fa-battery-full';
        opt_2 = 'fa fa-battery-three-quarters';
        let sidebar_icon = $("#sidebar-em");

        (sidebar_icon.attr("class") === opt_1) ? sidebar_icon.attr("class", opt_2) : sidebar_icon.attr("class", opt_1);
    }, 1000);
});

$(document).ready(function() {
    setInterval(function () {
        quarter_id = 'session-view-battery-level-quarter-em';
        half_id = 'session-view-battery-level-half-em';
        three_quarters_id = 'session-view-battery-level-three-quarters-em';
        full_id = 'session-view-battery-level-full-em';

        empty_class = 'fa fa-battery-empty';
        quarter_class = 'fa fa-battery-quarter';
        half_class = 'fa fa-battery-half';
        three_quarters_class = 'fa fa-battery-three-quarters';
        full_class = 'fa fa-battery-full';

        let battery_level_icon = $('em').filter(function() {
                return this.id.match(/^session-view-battery-level-/);
            });

        if (battery_level_icon.attr("id") === quarter_id) {
            if (battery_level_icon.attr("class") === quarter_class) {
                battery_level_icon.attr("class", empty_class)
            } else {
                battery_level_icon.attr("class", quarter_class)
            }
        }

        if (battery_level_icon.attr("id") === half_id) {
            if (battery_level_icon.attr("class") === half_class) {
                battery_level_icon.attr("class", quarter_class)
            } else {
                battery_level_icon.attr("class", half_class)
            }
        }

        if (battery_level_icon.attr("id") === three_quarters_id) {
            if (battery_level_icon.attr("class") === three_quarters_class) {
                battery_level_icon.attr("class", half_class)
            } else {
                battery_level_icon.attr("class", three_quarters_class)
            }
        }

        if (battery_level_icon.attr("id") === full_id) {
            if (battery_level_icon.attr("class") === full_class) {
                battery_level_icon.attr("class", three_quarters_class)
            } else {
                battery_level_icon.attr("class", full_class)
            }
        }
    }, 1000);
});

$('#per_page').change(function(ev) {
    window.location.href = BASE_URL + '/tools/per-page/' + $('#per_page').val();
});

$('.confirm').click(function(ev) {
    var message = $(this).data('confirm-message');

    if (message == null) {
        message = 'Are you sure?';
    }

    if (!confirm(message)) {
        ev.preventDefault();
    }
});
