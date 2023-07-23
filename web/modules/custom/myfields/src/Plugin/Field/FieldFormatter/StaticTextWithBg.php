<?php

namespace Drupal\myfields\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'my_module_static_text' formatter.
 *
 * @FieldFormatter(
 *   id = "static_bg",
 *   module = "my_module",
 *   label = @Translation("Static style-based formatter"),
 *   field_types = {
 *     "rgb_field"
 *   }
 * )
 */
class StaticTextWithBg extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#attributes' => [
          'style' => ['color:' . $item->value . ';font-size:50px'],
        ],
        '#value' => $item->value,
      ];
    }
    return $elements;
  }

}
