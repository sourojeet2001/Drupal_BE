<?php

namespace Drupal\myfields\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "rgb_text",
 *   module = "mymodule",
 *   label = @Translation("RGB value as #ffffff"),
 *   field_types = {
 *     "rgb_field"
 *   }
 * )
 */
class TextWidget extends CustomWidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = $items[$delta]->value ?? '';
    $user = $this->currentUser->getRoles();
    if (in_array('administrator', $user)) {
      $element += [
        '#type' => 'textfield',
        '#default_value' => $value,
        '#size' => 7,
        '#maxlength' => 7,
        '#element_validate' => [
          [$this, 'validate'],
        ],
      ];
      return ['value' => $element];
    }
  }

  /**
   * Validate the color text field.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) === 0) {
      $form_state->setValueForElement($element, '');
      return;
    }
    if (!Color::validateHex($value)) {
      $form_state->setError($element, $this->t('Color must be a 3- or 6-digit hexadecimal value, suitable for CSS.'));
    }
  }

}
