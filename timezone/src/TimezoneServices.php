<?php

namespace Drupal\timezone;

use Drupal\Core\Config\ConfigFactoryInterface;

class TimezoneServices {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;


  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory->get('timezone.timezone_configs');
  }

  /**
   * Function to Get Current Time based on TimeZone.
   * @return string $currentDate
   */
  public function getServiceData() {
    $selectedTimeZone = $this->configFactory->get("timezone");
    $currentDate = new \DateTime("now", new \DateTimeZone($selectedTimeZone) );
    return $currentDate->format('dS M Y H:i A');
  }

}
