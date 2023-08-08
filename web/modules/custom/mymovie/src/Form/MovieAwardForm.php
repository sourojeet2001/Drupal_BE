<?php

declare(strict_types=1);

namespace Drupal\mymovie\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mymovie\Entity\MovieAward;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Movie Award form.
 */
final class MovieAwardForm extends EntityForm {
  
  /**
  * Setting Constant of CONFIGNAME.
  */
  const CONFIGNAME = 'MovieAwardForm.settings';
  
  /**
   * Declaring variable of EntityTypeManagerInterface $entity_type_manager.
   */
  protected $entityTypeManager;

  /**
   * Constructor initializes the object of EntityTypeManagerInterface.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {

    $form = parent::form($form, $form_state);

    if (!$this->entity->isNew()) {
      $movie = $this->entityTypeManager->getStorage('node')->load($this->entity->get('movie_node'));
    }

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => [MovieAward::class, 'load'],
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->entity->status(),
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $this->entity->get('description'),
    ];

    $form['year'] = [
      '#type' => 'number',
      '#title' => $this->t('Year'),
      '#required' => TRUE,
      '#default_value' => $this->entity->get('year'),
    ];

    $form['movie_node'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Movie Title'),
      '#target_type' => 'node',
      '#required' => TRUE,
      '#default_value' => $movie,
      '#selection_settings' => [
        'target_bundles' => ['movie'],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $this->messenger()->addStatus(
      match ($result) {
        \SAVED_NEW => $this->t('Created new example %label.', $message_args),
        \SAVED_UPDATED => $this->t('Updated example %label.', $message_args),
      }
    );
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
