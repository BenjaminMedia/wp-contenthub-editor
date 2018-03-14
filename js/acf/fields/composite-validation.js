acf.add_filter('validation_complete', function( json, $form ){

    jQuery(document).ready(function($){

        //Flexible content class acf-field-58aae476809c6
        var leadImagesSelector = ".acf-field-58aae476809c6 .acf-flexible-content .values .acf-fields [data-name='lead_image'] input:checkbox";
        var leadImageCheckboxes = $(leadImagesSelector);
        var checkedLeadImages = 0;

        //Loop through available lead images
        leadImageCheckboxes.each(function() {

            if($(this).attr('checked') === 'checked')
            {
                checkedLeadImages++;
            }

            if(checkedLeadImages > 1)
            {
                var leadImageError = {input: $(this).attr('name'), message: "Please make sure you only select 1 Lead image!"};

                if(typeof json.errors.length === 'undefined')
                {
                    json.errors = [];
                }

                json.errors.push(leadImageError);
                //invalidate the form
                json.valid = 0;

                //Exit loop
                return false;
            }
        });

        var teaserImageCheckbox = $(".acf-field-58aae476809c6 .acf-flexible-content .values .acf-fields [data-name='teaser_image'] input:checkbox");
        var teaserImage = $('.acf-field-58e38da2194e3 input').val();

        if(teaserImage > 0 && teaserImageCheckbox.attr('checked') === 'checked')
        {
            var teaserImageError = {input: $(teaserImageCheckbox).attr('name'), message: "Please make sure you have only 1 teaser image!"};

            if(typeof json.errors.length === 'undefined')
            {
                json.errors = [];
            }

            json.errors.push(teaserImageError);
            //invalidate the form
            json.valid = 0;

        }

    });

    // return
    return json;
});