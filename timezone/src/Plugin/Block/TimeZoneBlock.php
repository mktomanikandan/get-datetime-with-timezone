<?php
namespace Drupal\timezone\Plugin\block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\timezone\TimezoneServices;

/**
 * Provides my custom block.
 *
 * @Block(
 *   id = "timezone_block",
 *   admin_label = @Translation("Get Date and Time Based on TimeZone."),
 *   category = @Translation("Blocks")
 * )
 */
class TimeZoneBlock extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {



  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   *
   */
  protected $entityManager;

    /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal\timezone\TimeZoneService definition.
   *
   * @var Drupal\timezone\TimeZoneService
   */
  protected $timeZoneService;

  /**
   * {@inheritdoc}
   */
 public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityManager, ConfigFactoryInterface $configFactory, TimezoneServices $timeZoneService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityManager;
    $this->configFactory = $configFactory;
    $this->timeZoneService = $timeZoneService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('timezone.timezoneservices')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    // Timezone admin config form Data
    $config = $this->configFactory->get('timezone.timezone_configs');
    $country = $config->get('country');
    $city = $config->get('city');
    $timezone = $config->get('timezone');
    if(!empty($timezone)) {
      $dateTime = $this->timeZoneService->getServiceData();
      if(!empty($dateTime)) {
        return [
          '#type' => 'markup',
          '#markup' => render($dateTime),
          '#cache' => [
            'max-age' => 0,
          ],
        ];
      }
      else {
        return [];
      }
    }
    else {
      return [];
    }
  }
}
