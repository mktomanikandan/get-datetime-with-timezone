<?php

namespace Drupal\timezone\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheTagsInvalidator;


/**
 * An TimeZone Config Form.
 */
class TimeZoneForm extends ConfigFormBase {

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Drupal\Core\Cache\CacheTagsInvalidator definition.
   *
   * @var Drupal\Core\Cache\CacheTagsInvalidator
   */
  protected $cacheTags;

  /**
   * Constructs a new DeltaCacheService object.
   */
  public function __construct(ConfigFactoryInterface $configFactory, CacheTagsInvalidator $cacheTags) {
    $this->configFactory = $config_factory;
    $this->cacheTags = $cacheTags;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'timezone.timezone_configs',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'timezone_configs';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('timezone.timezone_configs');

    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('country'),
      '#description' => $this->t('Enter country name.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('city'),
      '#description' => $this->t('Enter city name.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('city'),
    ];

    $form['timezone'] = [
        '#type' => 'select',
        '#title' => $this->t('Select timezone'),
        '#options' => [
          'America/Chicago' => $this->t('America/Chicago'),
          'America/New_York' => $this->t('America/New_York'),
          'Asia/Tokyo' => $this->t('Asia/Tokyo'),
          'Asia/Dubai' => $this->t('Asia/Dubai'),
          'Asia/Kolkata' => $this->t('Asia/Kolkata'),
          'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
          'Europe/Oslo' => $this->t('Europe/Oslo'),
          'Europe/London' => $this->t('Europe/London'),
        ],
        '#default_value' => $config->get('timezone'),
      ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('timezone.timezone_configs')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
    $this->cacheTags->invalidateTags(array("block_view:timezone_block"));
   }
}
