<?php

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Psr\Container\ContainerInterface;

/**
 * Provides "custom twig form" block.
 *
 * @Block(
 *  id = "custom twig form",
 *  admin_label = @Translation("Custom Twig Form"),
 *  category = @Translation("Custom Block")
 * )
 */
class CustomFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

/**
 * @var conn Drupal\Core\Database\Connection
 */
  protected $conn;

  /**
   * @var config Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database'),
      $container->get('config.factory'),
    );
  }

  /**
   * Constructs a HelloWorld object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param object $conn
   *   Object of Connection.
   * @param object $config
   *   Object of ConfigFactoryInterface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $conn, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->conn = $conn;
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $result = $this->config->get('BlockForm.settings')->get('data');
    return [
      '#theme' => 'block-form',
      '#data' => $result,
      '#attached' => [
        'library' => [
          'mymodule/form_style',
        ],
      ],
    ];
  }

}
