<?php

namespace Drupal\myfields\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "color_picker",
 *   module = "mymodule",
 *   label = @Translation("Color Picker"),
 *   field_types = {
 *     "rgb_field"
 *   }
 * )
 */
class ColorPickerWidget extends CustomWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = $items[$delta]->value ?? '';
    $user = $this->currentUser->getRoles();
    if (in_array('administrator', $user)) {
      $element += [
        '#type' => 'color',
        '#default_value' => $value,
      ];
      return ['value' => $element];
    }
  }

}
