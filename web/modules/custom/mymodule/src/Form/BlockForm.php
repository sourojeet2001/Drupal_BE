<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * This class is used to define a config form which can add or remove fieldgroups.
 */
class BlockForm extends ConfigFormBase {

  /**
   * Setting Constant of CONFIGNAME.
   */
  const CONFIGNAME = 'BlockForm.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'blockform__table';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [static::CONFIGNAME];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(static::CONFIGNAME);
    $form_data = $config->get('data') ?: [];
    if (empty($form_data)) {
      $form_data = [];
    }
    // Store the form_data in $form_state for later use.
    $form_state->set('form_data', $form_data);

    $num_lines = $form_state->get('num_lines');
    if ($num_lines === NULL) {
      $form_state->set('num_lines', count($form_data) - 1);
      $num_lines = $form_state->get('num_lines');
    }

    $details = $form_state->get('details');
    if (!$details) {
      $details = [];
      for ($i = 1; $i <= $num_lines; $i++) {
        $details[$i] = [
          'name' => '',
          'label1' => '',
          'count' => '',
          'status' => 'active',
        ];
      }
      $form_state->set('details', $details);
    }

    $form['#tree'] = TRUE;
    $form['details'] = [
      '#type' => 'table',
      '#title' => 'Sample Table',
      '#header' => ['Company Name', 'Label', 'Count', 'Remove'],
      '#prefix' => '<div id="my-table-wrapper">',
      '#suffix' => '</div>',
    ];

    // Render only the active fieldsets.
    foreach ($details as $i => $fieldset) {
      if ($fieldset['status'] === 'active') {
        $form_data = $form_state->get('form_data');
        $form['details'][$i]['name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Name'),
          '#title_display' => 'invisible',
          '#default_value' => $form_data[$i]['name'] ?? '',
        ];

        $form['details'][$i]['label1'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Label'),
          '#title_display' => 'invisible',
          '#default_value' => $form_data[$i]['label1'] ?? '',
        ];

        $form['details'][$i]['count'] = [
          '#type' => 'number',
          '#title' => $this->t('Count'),
          '#title_display' => 'invisible',
          '#default_value' => $form_data[$i]['count'] ?? '',
        ];

        $form['details'][$i]['remove'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#name' => $i,
          '#submit' => ['::removeAjax'],
          '#ajax' => [
            'callback' => '::addAjax',
            'wrapper' => 'my-table-wrapper',
            'progress' => [
              'type' => 'throbber',
              'message' => $this->t('Removing Field...'),
            ],
          ],
        ];
      }
    }

    $form['details']['actions']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addAjax',
        'wrapper' => 'my-table-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Adding Field...'),
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * This function is used to update the num_lines variable and rebuild the
   * form based on its value.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $num_lines = $form_state->get('num_lines');
    $num_lines++;
    $form_state->set('num_lines', $num_lines);

    // Add a new fieldset to the form state with default values.
    $details = $form_state->get('details');
    $details[$num_lines] = [
      'name' => '',
      'label1' => '',
      'count' => '',
      'status' => 'active',
    ];
    $form_state->set('details', $details);

    $form_state->setRebuild();
  }

  /**
   * This function is used to render the form once again.
   */
  public function addAjax(array &$form, FormStateInterface $form_state) {
    return $form['details'];
  }

  /**
   * This function is used to remove a fieldgroup from the whole config form.
   */
  public function removeAjax(array &$form, FormStateInterface $form_state) {
    $num_lines = $form_state->get('num_lines');
    if ($num_lines == 1) {
      return $form['details'];
    }
    else {
      $trigger = $form_state->getTriggeringElement();
      $indexToRemove = $trigger['#name'];
      $details = $form_state->get('details');
      // Mark the fieldset as removed.
      $details[$indexToRemove]['status'] = 'removed';
      $form_state->set('details', $details);
      $form_state->set('num_lines', $num_lines - 1);
      $form_state->setRebuild();
    }
    return $form['details'];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Collect data from active fieldsets.
    $config = $this->config(static::CONFIGNAME);
    $form_data = $form_state->getValues()['details'];
    $config->set('data', $form_data);
    $config->save();
    // Process and save the data as needed.
    parent::submitForm($form, $form_state);
  }

}
