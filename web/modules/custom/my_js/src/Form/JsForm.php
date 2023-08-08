<?php

namespace Drupal\my_js\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This form is used to take user input of a telephone number.
 */
class JsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'js_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['tele'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Telephone No'),
      '#size' => 25,
      '#description' => $this->t("Please enter the telephone number"),
      '#required' => TRUE,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
