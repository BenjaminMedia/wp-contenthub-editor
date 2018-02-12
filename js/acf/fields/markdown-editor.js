(function () {
    function createSimpleMde(textArea, options) {
        var mdeOptions = {
            element: textArea,
            spellChecker: true,
        };
        if(typeof dictionary !== "undefined") {
            mdeOptions.dictionary = dictionary;
        }
        new SimpleMDE(Object.assign(mdeOptions, options));
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
                createSimpleMde(this, jQuery(this).data('simple-mde-config'));
            }
        })
        
    });
    
})();
