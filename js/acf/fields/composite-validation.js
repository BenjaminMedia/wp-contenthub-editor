acf.add_filter('validation_complete', function( json, $form ){

    jQuery(document).ready(function($){

        //Flexible content class acf-field-58aae476809c6
        var leadImagesSelector = ".acf-field-58aae476809c6 .acf-flexible-content .values .acf-fields [data-name='lead_image'] input:checkbox";
        var leadImageCheckboxes = $(leadImagesSelector);
        var checkedLeadImages = 0;

        // Make sure the user hasn't checked more than one 'Lead Image'
        // Loop through available lead images
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

        var videoTeaserImageCheckbox = $(".acf-field-58aae476809c6 .acf-flexible-content .values .acf-fields [data-name='video_teaser_image'] input:checkbox");
        var teaserImage = $('.acf-field-58e38da2194e3 input').val();

        // Make sure there is no error with 'Teaser Image value is required' if a video is present with 'Teaser Image' checked
        if(videoTeaserImageCheckbox.attr('checked') === 'checked') {
            for(var i=0; i<json.errors.length; i++) {
                if(json.errors[i].input == 'acf[field_58e38da2194e3]') {
                    json.errors.splice(i, 1);
                    break;
                }
            }
        }

        // Make sure the user hasn't selected both a Video Teaser Image and a Teaser Image
        if(teaserImage > 0 && videoTeaserImageCheckbox.attr('checked') === 'checked')
        {
            var teaserImageError = {input: $(videoTeaserImageCheckbox).attr('name'), message: "Please make sure you have only 1 teaser image!"};

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
