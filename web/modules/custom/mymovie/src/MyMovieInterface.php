<?php

namespace Drupal\mymovie;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a my movie entity type.
 */
interface MyMovieInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
