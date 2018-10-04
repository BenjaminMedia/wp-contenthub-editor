(function () {

  var activeFocalPoint = null;

  acf.add_action('append', function (el) {
    attachClickEventToButtons(el);
  });

  acf.add_action('ready', function (el) {
    attachClickEventToButtons(el);
  });

  function attachClickEventToButtons(el) {
    jQuery(el).find('.edit-hotspot-image').each(function () {
      if (jQuery(this).is(":visible")) { // Only render visible elements
        jQuery(this).click(function(e) {
          toggleFocalPoint(this, jQuery(this).data('input-id'));
        })
      }
    })
  }

  function toggleFocalPoint(editBtn, inputId) {
    var image = jQuery(editBtn).parents('div.layout').find('img');
    var input = jQuery('#'+inputId);

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
