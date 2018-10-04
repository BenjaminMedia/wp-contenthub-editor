(function ($) {

  var activeFocalPoint = null;

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
          toggleFocalPoint(this, $(this).data('input-id'));
        })
      }
    })
  }

  function toggleFocalPoint(editBtn, inputId) {
    var image = $(editBtn).parents('div.layout').find('img');
    var input = $('#'+inputId);

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
