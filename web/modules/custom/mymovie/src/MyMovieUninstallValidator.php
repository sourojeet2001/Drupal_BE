<?php

namespace Drupal\mymovie;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Prevents mymovie module from being uninstalled under certain conditions.
 *
 * These conditions are when any movie nodes exist.
 */
class MyMovieUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface $entityTypeManager.
   */
  protected $entityTypeManager;

  /**
   * Constructs a new MyMovieUninstallValidator.
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * 
   * @param TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TranslationInterface $string_translation) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module == 'mymovie') {
      // The movie node type is provided by this module. Prevent uninstall
      // if there are any nodes of that type.
      if ($this->hasMovieNodes()) {
        $reasons[] = $this->t('To uninstall My Movie, delete all content that has the Movie content type');
      }
    }
    return $reasons;
  }

  /**
   * Determines if there is any movie nodes or not.
   *
   * @return bool
   *   TRUE if there are movie nodes, FALSE otherwise.
   */
  protected function hasMovieNodes() {
    $nodes = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'movie')
      ->accessCheck(FALSE)
      ->range(0, 1)
      ->execute();
    return !empty($nodes);
  }

}
