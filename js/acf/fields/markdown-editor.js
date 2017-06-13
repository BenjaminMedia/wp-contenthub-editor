(function () {

  function createSimpleMde(textArea, options) {
    new SimpleMDE(Object.assign({
      element: textArea,
      spellChecker: false
    }, options));
  };

  acf.add_action('append', function( el )
  {
    var textArea = jQuery(el).find('.acf-field-simple-mde');
    if(typeof textArea[0] !== 'undefined')
      createSimpleMde(textArea[0], textArea.data('simple-mde-config'));
  });

  acf.add_action('ready', function( el ){

    jQuery(el).find('.acf-field-simple-mde').each(function()
    {
      if(jQuery(this).is(":visible")) { // Only render visible elements
        createSimpleMde(this, this.dataset.simpleMdeConfig);
      }
    })

  });

})();
