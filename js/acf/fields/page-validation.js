acf.addFilter('validation_complete', function (json, $form) {
  $ = jQuery;
  var teaserListPaginationsChecked = $(".acf-field-5bb318f2ffcef .acf-flexible-content .values .acf-fields [data-name='pagination'] input:checkbox:checked");

  console.log(teaserListPaginationsChecked.length);

  if (teaserListPaginationsChecked.length > 1) {
    console.log('More Than One!');
    teaserListPaginationsChecked.slice(1).each(function () {
      var paginationError = {input: $(this).attr('name'), message: 'Please make sure you only select 1 paginated teaser list!'};

      if (typeof json.errors.length === 'undefined') {
        json.errors = [];
      }

      json.errors.push(paginationError);

      json.valid = 0;
      console.log(json);
    });
  }

  return json;
});
