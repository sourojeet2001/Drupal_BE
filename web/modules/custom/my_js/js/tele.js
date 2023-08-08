/**
 * This function is used to format the number in the specified format,
 * immediately when the number is of 10 digit.
 */
(function ($) {
  Drupal.behaviors.telFormatter = {
    attach: function (context, settings) {
      $("input[type='text'][name='tele']", context).keyup(function() {
        var value = $(this).val();
        var length = value.length;
        var formatted = '';
        if(length == 10) {
          for(var i =0; i < 10; i++) {
            if(i == 0) {
              formatted += '(';
            }
            if(i == 3) {
              formatted += ') ';
            }
            if(i == 6) {
              formatted += ' - ';
            }
            formatted += value[i];
          }
          $(this).val(formatted);
        }
      });
    }
  };
})(jQuery);