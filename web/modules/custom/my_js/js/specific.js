/**
 * This function is used to add styling to a specific node.
 */
(function ($) {
  Drupal.behaviors.myModuleBehavior = {
    attach: function (context, settings) {
      $('.field.field--name-price.field--type-decimal.field--label-hidden.field__item', context).on('click',function() {
        $(this).css('color', 'red');
      });
    }
  };
})(jQuery);
