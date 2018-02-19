function closeAllModal() {
    if ($('.modal').is(":visible")) {
        $('.modal').fadeOut(200);
    } else {
        console.log("Modal Not visible")
    }
}
$(document).on('click', '#modalLaunch', function () {
    var launchid = $(this).attr('launch');
    if ($('#' + launchid).is(":visible")) {
        console.log("ERROR: modal already open");
    } else {
        $('#' + launchid).fadeIn(200);
    }
});
$(document).on('click', '#close', function () {
    closeAllModal()
});
$(document).on('click', '.modal', function (e) {
    var target = $(e.target);
    if (target.is(".modal")) {
        closeAllModal();
    }
});
function linkify(text) {
    var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(urlRegex, function (url) {
        return '<a target="_blank" href="' + url + '">' + url + '</a>';
    });
}