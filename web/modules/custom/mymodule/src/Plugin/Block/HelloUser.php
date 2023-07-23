<?php

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Psr\Container\ContainerInterface;

/**
 * Provides "Hello User" block, which shows a greeting with user role.
 *
 * @Block(
 *  id = "hello user",
 *  admin_label = @Translation("Hello User"),
 *  category = @Translation("Custom Block")
 * )
 */
class HelloUser extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var account \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
    );
  }

  /**
   * Constructs a HelloUser object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param object $account
   *   Object of AccountProxyInterface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxyInterface $account) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $roles = $this->account->getRoles();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello @role', ['@role' => $roles[1]]),
    ];
  }

}
