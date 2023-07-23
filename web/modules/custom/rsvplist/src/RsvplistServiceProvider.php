<?php

namespace Drupal\rsvplist;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

// @note: You only need Reference, if you want to change service arguments.
use Symfony\Component\DependencyInjection\Reference;

/**
 * Modifies the language manager service.
 */
class RsvplistServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */  
  public function alter(ContainerBuilder $container) {
    if ($container->hasDefinition('rsvplist.emailValidator')) {
      $definition = $container->getDefinition('rsvplist.emailValidator');
      $definition->setClass('Drupal\rsvplist\EmailAlter');
      $definition->addTag('rsvplist_altered_service');
    }
  }

}