<?php

/**
 * @file
 * Generates markup  to be displayed. Functionality in this controller is wired
 * to Drupal in mymodule.routing.yml.
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;
use Drupal\mymodule\CurrentUserCustom;
use Drupal\user\UserInterface;
use Psr\Container\ContainerInterface;

/**
 * This class controls all the route redirection by specifying different functions.
 */
class FirstController extends ControllerBase {  

  protected $currUser;
  
  /**
   * Class constructor.
   */
  public function __construct(CurrentUserCustom $currUser) {
    $this->currUser = $currUser;
  }
  
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('mymodule.currentUserCustom')
    );
  }

  /**
   * This function is used to render a simple page using a Hello message.
   *
   */
  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => t(string: 'Hello Sourojeet'),
    ];
  }
  
  /**
   * This fucntion is used to display dynamic content as per query parameteres.
   *
   *  @param  string $name1
   *    Takes a name as query parameter.
   *  @param  string $name2
   *    Takes another name as query parameter.
   */
  public function variableContent(string $name1, string $name2) {
    return [
      '#type' => 'markup',
      '#markup' => t(string: 'Hello @name1 and @name2',
        args: ['@name1' => $name1, '@name2' => $name2]),
    ];
  }
    
  /**
   * This function is used to display a hello message with the username of the 
   * currently logged in user by using a core service of Drupal.
   *
   */
  public function hello() {
    // Fetching the current user id.
    // $user = \Drupal::currentUser()->id();
    $user = $this->currUser;
    if($user) {
      // $cacheTags = ['user:' . \Drupal::currentUser()->id()];
      $cacheTags = ['user:' . $user->getCurrUserId()];
      // Rendering back the hello message with currently logged in user's name.
      return [
        '#type' =>'markup',
        '#cache' => [
          'tags' => $cacheTags,
        ],
        '#markup' => t(string: 'Hello ' . $user->getAccountName()),
      ];
    }
    // For anonymous user just shows a "Hello User" message.
    else {
      return [
        '#type' =>'markup',
        '#markup' => t(string: 'Hello User'),
      ];
    }
  }
  
  /**
   * This is a function just for testing purpose.
   *
   */
  public function test() {
    \Drupal::messenger()->addMessage(t("Hello Sourojeet Test Successful"));
  }
}