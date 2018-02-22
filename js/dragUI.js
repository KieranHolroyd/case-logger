$(document).ready(function () {
    
var ls = window.localStorage;
$('#nav').draggable({ scroll: false });
$('#moreMenu').draggable({ scroll: false });
$(document).ready(function () {
    if (ls.getItem('navPosition') !== "") {
        var navPos = JSON.parse(ls.getItem('navPosition'));
        $('#nav').css("top", navPos.top);
        $('#nav').css("left", navPos.left);
    }
});
$('#openMore').click(function (e) {
    if ($(this).attr('opened') == "false") {
        var x = e.pageX - $('#topLevel').offset().left;
        var y = e.pageY - $('#topLevel').offset().top;
        $('#moreMenu').css("left", (x -= 100) + "px");
        $('#moreMenu').css("top", (y += 30) + "px");
        $('#moreMenu').slideDown(200);
        $(this).attr('opened', "true");
    } else {
        $('#moreMenu').slideUp(200);
        $(this).attr('opened', "false");
    }
});
$(document).click(function (e) {
        if (e.target.id == 'moreMenu' || e.target.id == 'openMore' || e.target.parentElement.id == 'moreMenu' || e.target.id == 'nav' || e.target.parentElement.id == 'nav') {
        } else {
            $('#moreMenu').slideUp(200);
            $('#openMore').attr('opened', "false");
    }
// if (e.target.id == 'contextMenu' || e.target.parentElement.id == 'contextMenu') { } else {
    //     $('#contextMenu').slideUp(100);
    // }
});
// $(document).on("contextmenu", function (e) {
    //     e.preventDefault();
    //     var x = e.pageX - $('#topLevel').offset().left;
    //     var y = e.pageY - $('#topLevel').offset().top;
    //     $('#contextMenu').css("left", x + "px");
    //     $('#contextMenu').css("top", y + "px");
    //     $('#contextMenu').slideDown(100);
    // });
    setInterval(function () {
        ls.setItem("navPosition", JSON.stringify($('#nav').position()));
    }, 10000);
    //Stops the bar going off the screen
    $(window).resize(function () {
        if ($(window).width() - ($('#nav').offset().left + $('#nav').outerWidth(true)) < 50) {
            $('#nav').css('left', '200px');
        }
        if ($(window).height() - ($('#nav').offset().top + $('#nav').outerHeight(true)) < 50) {
            $('#nav').css('top', '200px');
        }
    });
});