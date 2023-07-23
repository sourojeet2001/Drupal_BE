<?php

/**
 * @file
 * Generates markup  to be displayed. Functionality in this controller is wired
 * to Drupal in flagship.routing.yml.
 */

namespace Drupal\flagship\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * This class controls all the route redirection by specifying different functions.
 */
class BlockController extends ControllerBase {  

  /**
   * This function is used to render a simple page using a Hello message.
   *
   */
  public function customPage() {
    return [
      '#type' => 'markup',
      '#markup' => t(string: 'Hello Sourojeet'),
    ];
  }
  
  
}