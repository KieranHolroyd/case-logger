//Fixed Top Nav Gets A Shadow When User Scrolls
//Smooth Anchor Points
$('a[href*="#"]')
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top-110
        }, 1000, function() {
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) {
            return false;
          } else {
            $target.attr('tabindex','-1');
            $target.focus();
          };
        });
      }
    }
  });
  function closeAllModal() {
    if ($('.modal').is(":visible")) {
      $('.modal').fadeOut(200);
    } else {
      console.log("Modal Not visible")
    }
  }
	$(document).on('click', '#modalLaunch', function () {
    var launchid = $(this).attr('launch');
    if ($('#'+launchid).is(":visible")) {
      console.log("ERROR: modal already open");
    } else {
      $('#'+launchid).fadeIn(200);
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
    var urlRegex =/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(urlRegex, function(url) {
      return '<a target="_blank" href="' + url + '">' + url + '</a>';
    });
	}