<?php

namespace Drupal\myfields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'my_module_static_text' formatter.
 *
 * @FieldFormatter(
 *   id = "static_text",
 *   module = "my_module",
 *   label = @Translation("Static text-based formatter"),
 *   field_types = {
 *     "rgb_field"
 *   }
 * )
 */
class StaticTextFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#markup' => $item->value,
      ];
    }

    return $elements;
  }

}
