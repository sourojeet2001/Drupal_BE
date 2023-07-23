<?php

namespace Drupal\myfields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "rgb_val",
 *   module = "mymodule",
 *   label = @Translation("RGB"),
 *   field_types = {
 *     "rgb_field"
 *   }
 * )
 */
class RGBFieldWidget extends CustomWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Set up the form element for this widget.
    $user = $this->currentUser->getRoles();
    if (in_array('administrator', $user)) {
      $element += [
        '#type' => 'textfield',
        '#default_value' => $items[$delta]->value ?? '',
        '#size' => 15,
        '#field_prefix' => 'RGB: ',
        '#attributes' => ['class' => ['rgb-textfield']],
      ];

      // Add a custom submit callback to convert RGB values to hex.
      $element['#element_validate'][] = [$this, 'combineRgbValues'];

      return ['value' => $element];
    }
  }

  /**
   * Custom submit callback to combine RGB values into a single hex value.
   */
  public static function combineRgbValues(array &$element, FormStateInterface $form_state, array &$complete_form) {
    $rgb_value = $form_state->getValue($element['#parents']);
    $rgb_values = explode(',', $rgb_value);

    $r = isset($rgb_values[0]) ? (int) trim($rgb_values[0]) : 0;
    $g = isset($rgb_values[1]) ? (int) trim($rgb_values[1]) : 0;
    $b = isset($rgb_values[2]) ? (int) trim($rgb_values[2]) : 0;

    // Validate the RGB values (you can add more validation if needed).
    if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255) {
      $form_state->setError($element, t('Invalid RGB value. Each value should be an integer between 0 and 255.'));
      return;
    }

    // Convert RGB to hex.
    $hex_value = sprintf("#%02x%02x%02x", $r, $g, $b);

    // Update the value to the hex value.
    $form_state->setValueForElement($element, $hex_value);
  }

}
