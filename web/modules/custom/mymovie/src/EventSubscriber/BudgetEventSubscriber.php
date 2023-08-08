<?php

namespace Drupal\mymovie\EventSubscriber;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EntityTypeSubscriber.
 *
 * @package Drupal\mymovie\EventSubscriber
 */
class BudgetEventSubscriber implements EventSubscriberInterface {
  
  /**
   * Intializes the object of CurrentRouteMatch.
   *
   * @var CurrentRouteMatch $routeMatch
   */
  protected $routeMatch;

  /**
   * Intializes the object of Messenger.
   *
   * @var Messenger $messenger
   */
  protected $messenger;
  
  /**
   * Intializes the object of ConfigFactory.
   *
   * @var ConfigFactory $configuration.
   */
  protected $configuration;

  /**
   * Constructor to initialize objects.
   */
  public function __construct(CurrentRouteMatch $route_match, Messenger $messenger, ConfigFactory $configuration) {
    $this->routeMatch = $route_match;
    $this->messenger = $messenger;
    $this->configuration = $configuration;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_route_match'),
      $container->get('messenger'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   *   The event names to listen for, and the methods that should be executed.
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['budgetFriendly'],
    ];
  }

  /**
   *
   */
  public function budgetFriendly(RequestEvent $event) {
    // Check if the current route is for viewing a node entity.
    if ($this->routeMatch->getRouteName() === 'entity.node.canonical') {
      $node = $this->routeMatch->getParameter('node');
      // Ensure that the loaded entity is a node entity of type 'movie'.
      if ($node->getType() === 'movie') {

        $movie_price = $node->get('price')->value;
        $budget = $this->configuration->get('BudgetForm.settings')->get('budget');

        if ($movie_price < $budget) {
          $this->messenger->addMessage(t('The movie is under budget'));
        }
        elseif ($movie_price > $budget) {
          $this->messenger->addMessage(t('The movie is over budget'));
        }
        else {
          $this->messenger->addMessage(t('The movie is within budget'));
        }
      }
    }
  }

}
