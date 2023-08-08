<?php

namespace Drupal\mymovie\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the my movie entity edit forms.
 */
class MyMovieForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New my movie %label has been created.', $message_arguments));
        $this->logger('mymovie')->notice('Created new my movie %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The my movie %label has been updated.', $message_arguments));
        $this->logger('mymovie')->notice('Updated my movie %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.my_movie.canonical', ['my_movie' => $entity->id()]);

    return $result;
  }

}
