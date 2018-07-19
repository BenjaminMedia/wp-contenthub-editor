(function () {
  function quote(editor) {
    var cm = editor.codemirror;
    var selectedText = cm.getSelection();
    var text = selectedText || '';
    cm.replaceSelection('<cite>' + text + '</cite>');
  }

  function createSimpleMde(textArea, options) {
    var mdeOptions = {
      element: textArea,
      spellChecker: true,
    };
    if (typeof dictionary !== "undefined") {
      mdeOptions.dictionary = dictionary;
    }
    var toolbar = {
      toolbar: [
        "bold",
        "italic",
        "quote",
        {
          name: "cite",
          action: quote,
          className: "fa fa-star",
          title: "Citation",
        },
        "heading-2",
        "heading-3",
        "|",
        "unordered-list",
        "ordered-list",
        {
          name: "link",
          action: createLinkModal,
          className: "fa fa-link",
          title: "Create Link"
        },
        "|",
        "preview",
        "guide"
      ]
    };

    if ('simple' === options) {
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

  acf.add_action('append', function (el) {
    var textArea = jQuery(el).find('.acf-field-simple-mde');
    if (typeof textArea[0] !== 'undefined' && textArea.is(':visible'))
      createSimpleMde(textArea[0], textArea.data('simple-mde-config'));
  });

  acf.add_action('ready', function (el) {

    jQuery(el).find('.acf-field-simple-mde').each(function () {
      if (jQuery(this).is(":visible")) { // Only render visible elements
        createSimpleMde(this, jQuery(this).data('simple-mde-config'));
      }
    })

  });

  function createLinkModal(editor) {
    var cm = editor.codemirror;
    var selectedText = cm.getSelection();
    var text = '';
    var url = '';
    var title = '';
    var target = 'off';
    var nofollow = 'on';
    if(selectedText) {
      var markdownMatch = selectedText.match(/\[(.*)\]\(([^\s]+) ?(.*)?\)/);
      if(markdownMatch) {
        text = markdownMatch[1];
        url = markdownMatch[2];
        if(markdownMatch[3]) {
          var attrs = JSON.parse(markdownMatch[3]);
          if(attrs.hasOwnProperty('title')) {
            title = attrs.title;
          }
          if(attrs.hasOwnProperty('target')) {
            target = 'on';
          }
          if(attrs.hasOwnProperty('rel')) {
            nofollow = 'on';
          }
        }
      } else {
        text = selectedText;
      }
    }
    var modalContainer = jQuery(document.createElement('div'));
    modalContainer.css({
      'position': 'fixed',
      'top': 0,
      'left': 0,
      'right': 0,
      'bottom': 0,
      'min-height': '360px',
      'background': 'rgba(0,0,0,0.7)',
      'z-index': '159900'
    });
    var modal = jQuery(document.createElement('div'));
    modalContainer.append(modal);
    modal.css({
      'width': '500px',
      'position': 'absolute',
      'top': '20%',
      'left': '50%',
      'margin-left': '-250px',
      'background': '#fcfcfc',
      '-webkit-box-shadow': '0 5px 15px rgba(0,0,0,0.7)',
      'box-shadow': '0 5px 15px rgba(0,0,0,0.7)',
      'padding': '10px 20px'
    });
    modal.append(`
    <h3 style="text-center">Insert link</h3>
    <hr />
    <table class="form-table">
      <tbody>
        <tr>
            <td><label>Link text*</label></td>
            <td><input type="text" id="simpleMDE-link-text" style="width: 100%;" /></td>
        </tr>
        <tr>
            <td><label>Link URL*</label></td>
            <td><input type="text" id="simpleMDE-link-url" style="width: 100%;" /></td>
        </tr>
        <tr>
            <td><label>Link title</label></td>
            <td><input type="text" id="simpleMDE-link-title" style="width: 100%;" /></td>
        </tr>
        <tr>
            <td><label>Link target</label></td>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" checked name="simpleMDE-link-target" value="off" /> Same window
                    </label>
                    <br />
                    <label>
                        <input type="radio" name="simpleMDE-link-target" value="on" /> New window
                    </label>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td><label>Link REL</label></td>
            <td>
                <fieldset>
                    <label>
                        <input type="radio" name="simpleMDE-link-nofollow" value="off" /> Normal link
                    </label>
                    <br />
                    <label>
                        <input type="radio" checked name="simpleMDE-link-nofollow" value="on" /> Nofollow link
                    </label>
                </fieldset>
            </td>
        </tr>
      </tbody>
    </table>
    <hr />
    `);
    var btn = jQuery(document.createElement('button'));
    btn.addClass('button button-primary button-large').css({
      'float': 'right',
      'clear': 'both',
    }).text('Update');
    modal.append(btn);
    btn.click(function(){
      var output = '[';
      output += jQuery('#simpleMDE-link-text').val();
      output += '](';
      output += jQuery('#simpleMDE-link-url').val();
      var attributes = {};
      var title = jQuery('#simpleMDE-link-title').val();
      if(title) {
        attributes.title = title;
      }
      if(jQuery('input[name="simpleMDE-link-target"]:checked').val() === 'on') {
        attributes.target = '_blank';
      }
      if(jQuery('input[name="simpleMDE-link-nofollow"]:checked').val() === 'on') {
        attributes.rel = 'nofollow';
      } else {
        attributes.rel = 'follow';
      }
      if(Object.keys(attributes).length !== 0) {
        output += ' ' + JSON.stringify(attributes);
      }
      output += ')';
      cm.replaceSelection(output);
      modalContainer.remove();
    });
    jQuery(document).keyup(function(e) {
      if (e.keyCode === 27){
        modalContainer.remove();
      }
    });
    jQuery('body').append(modalContainer);
    jQuery('#simpleMDE-link-text').val(text);
    jQuery('#simpleMDE-link-url').val(url);
    jQuery('#simpleMDE-link-title').val(title);
    jQuery.each(jQuery('input[name="simpleMDE-link-target"]'), function() {
      jQuery(this).prop("checked", jQuery(this).val() === target);
    });
    jQuery.each(jQuery('input[name="simpleMDE-link-nofollow"]'), function() {
      jQuery(this).prop("checked", jQuery(this).val() === nofollow);
    });
  }
})();
