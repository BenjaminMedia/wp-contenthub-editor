(function ($) {

  var activeFocalPoint = null;

  acf.add_action('remove', function (el) {
    if($.contains(el, '.edit-hotspot-image') && activeFocalPoint instanceof FocalPoint) {
      activeFocalPoint.destroy();
      activeFocalPoint = null;
    }
  });

  acf.add_action('append', function (el) {
    attachClickEventToButtons(el);
  });

  acf.add_action('ready', function (el) {
    attachClickEventToButtons(el);
  });

  function attachClickEventToButtons(el) {
    $(el).find('.edit-hotspot-image').each(function () {
      if ($(this).is(":visible")) { // Only render visible elements
        $(this).click(function(e) {
          toggleFocalPoint(this);
        })
      }
    })
  }

  function toggleFocalPoint(editBtn) {
    var image = $(editBtn).parents('div.layout').find('img');
    var input = $(editBtn).parent().find('input[type="hidden"]');

    if(image.attr('src') === 'undefined') {
      alert('You must select a image first')
      return;
    }

    if(activeFocalPoint instanceof FocalPoint) {
      activeFocalPoint.destroy();
    }

    activeFocalPoint = new FocalPoint(image[0], input[0]);
  }
})(jQuery);
