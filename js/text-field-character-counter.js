(function () {
  document.addEventListener("DOMContentLoaded", initCounters)

  function getSelectors() {
    const selectors = [
      '#title', // SELECTING THE ARTICLE TITLE FIELD
      '#acf-field_58abfebd21b82', // SELECTING THE ARTICLE DESCRIPTION FIELD
      '.acf-field-flexible-content .acf-input input[type="text"]:not([disabled])', // FILTERING OUT FIELDS LIKE FOCALPOINT ETC.
      '.acf-field-flexible-content .acf-input textarea:not(.acf-field-simple-mde)' // FILTERING OUT THE HIDDEN TEXTAREAS CONNECTED TO THE MARKDOWN FIELDS
    ]
    
    return selectors.join()
  }

  function getSelectorsWhenAddingContent() {
    const selectors = [
      '.acf-input input[type="text"]:not([disabled])',
      '.acf-input textarea:not(.acf-field-simple-mde)'
    ]

    return selectors.join()
  }

  function addCountersAndEventListeners(el) {
    console.log('addCountersAndEventListeners')
    const initialCharacterCount = el.value ? characterCount(el.value) : 0
    // PREVENT COUNTER TO BE ADDED DOUBLE TO TEXTAREAS FOR SOME WEIRD REASON
    if (!jQuery(el).next().hasClass('composite-character-counters')) {
      jQuery('<div class="composite-character-counters" style="text-align: right;">Initial Characters: <span class="composite-initial-character-counter" style="margin-right: 5px;">' + initialCharacterCount + '</span>Characters: <span class="composite-character-counter">' + initialCharacterCount + '</span></div>').insertAfter(el)
    }
    el.addEventListener('keyup', countCharacters)
    // TODO Attach eventListener for keyup on the specfic input on focus and remove on blur to save resources
    // el.addEventListener('focus', addKeyUpListener)
    // el.addEventListener('blur', addKeyUpListener)
  }

  function initCounters() {
    windowReady = true
    console.log(jQuery(getSelectors()).toArray())
    console.log(jQuery(getSelectors()))
    const textInputs = jQuery(getSelectors()).css('background-color', 'green').toArray();
    textInputs.forEach(function(el) {
      // if (!excludedWidgetType(el.data('layout'))) {
        addCountersAndEventListeners(el)
      // }
    });
  }

  let windowReady = false
  let isRunning = false
  function sumUpAllNonMarkdownFields() {
    if (isRunning || !windowReady) {
      return
    }
    isRunning = true
    const compositeCounters = jQuery('.composite-character-counter').toArray()
    let total = 0
    compositeCounters.forEach(function(item) {
      const value = item.innerHTML;
      total += parseInt(value)
    })
    document.getElementById('wp-admin-bar-character-count').innerHTML = "<span style='margin: 0 10px;'>Characters: " + total + "</span>";
    isRunning = false
  }
  
  function countCharacters(e) {
    let countTimeout
    let el = e.target
    jQuery(el).next().find('.composite-character-counter').get(0).innerHTML = el.value.length;

    window.clearTimeout(countTimeout)
    countTimeout = window.setTimeout(() => {
      sumUpAllNonMarkdownFields();
    }, 2000);
  }

  acf.addAction('append', function($el) {
    console.log($el)
    console.log($el.data('layout'))
    if (!excludedWidgetType($el.data('layout'))) {
      const textInputs = $el.find(getSelectorsWhenAddingContent()).css('background-color', 'orange').toArray();
      textInputs.forEach(function(el) {
        addCountersAndEventListeners(el)
      })
    }
  });

  function characterCount(string) {
    if (string.length === undefined) {
        return 0;
    }
    return string.replace(/\[.*]\(.*\)/g, '')
      .replace( /^[#]+[ ]+(.*)$/gm, '$1')
      .replace( /\*\*?(.*?)\*?\*/gm, '$1')
      .length;
  }

  function excludedWidgetType(type) {
    excludedWidgetTypes = [
      'audio',
      'file',
      'video',
      'link',
      'inserted_code',
      'sub_content',
      'inventory',
      'newsletter',
      'chapters_summary'
    ]
    return excludedWidgetTypes.includes(type)
  }
})();