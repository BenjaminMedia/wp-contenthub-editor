(function () {
    function createSimpleMde(textArea, options) {
        var mdeOptions = {
            element: textArea,
            spellChecker: true,
        };
        if(typeof dictionary !== "undefined") {
            mdeOptions.dictionary = dictionary;
        }
        var toolbar = {
            toolbar: [
                "bold",
                "italic",
                "quote",
                {
                    name: "cite",
                    action: function customFunction(editor){
                        var cm = editor.codemirror;
                        var output = '';
                        var selectedText = cm.getSelection();
                        var text = selectedText || '';
                        
                        output = '<cite>' + text + '</cite>';
                        cm.replaceSelection(output);
                    },
                    className: "fa fa-star",
                    title: "Citation",
                },
                "heading-2",
                "heading-3",
                "|",
                "unordered-list",
                "ordered-list",
                "link",
                "|",
                "preview",
                "guide"
            ]
        };
        
        if('infobox' === options) {
            toolbar = {
                toolbar: [
                    "bold",
                    "italic",
                    "|",
                    "unordered-list",
                    "ordered-list",
                    "link",
                    "|",
                    "preview",
                    "guide"
                ]
            };
        }
        new SimpleMDE(Object.assign(mdeOptions, toolbar));
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
