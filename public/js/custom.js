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
