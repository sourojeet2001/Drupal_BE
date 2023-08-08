<?php

namespace Drupal\mymovie\Form;

use Drupal\Core\Cache\Cache;
// use Drupal\Core\Cache\CacheTagsInvalidator;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This class is used to define a config form which is used to add movie budget.
 */
class BudgetForm extends ConfigFormBase {

  /**
   * Declaring variable of CacheTagsInvalidatorInterface.
   *
   * @var CacheTagsInvalidatorInterface $cache
   */
  protected CacheTagsInvalidatorInterface $cache;

  /**
   * Constructor to initialize objects.
   */
  public function __construct(CacheTagsInvalidatorInterface $cache) {
    $this->cache = $cache;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache_tags.invalidator'),
    );
  }

  /**
   * Setting Constant of CONFIGNAME.
   */
  const CONFIGNAME = 'BudgetForm.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'budgetform__table';
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
    $form['budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Budget'),
      '#default_value' => $config->get('budget'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIGNAME);
    $config->delete();
    $this->cache->invalidateTags(['node_list']);
    $config->set('budget', $form_state->getValue('budget'));
    $config->save();
  }

}
