<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 11:00 AM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;


use Drupal\aluminum\Aluminum\Traits\IdentifiableTrait;
use Drupal\aluminum_storage\Aluminum\Exception\ConfigException;
use Drupal\Core\Form\FormStateInterface;
use ReflectionClass;

class ConfigGroup implements ConfigGroupInterface {
  use IdentifiableTrait;

  protected $id;

  /** @var ConfigInterface */
  protected $config;

  protected $name;

  protected $weight;

  protected $configItems;

  public function __construct($id, ConfigInterface $config, $name = null, $weight = 0) {
    $this->id = $id;
    $this->config = $config;
    $this->name = $name;
    $this->weight = $weight;
    $this->configItems = $this->loadConfigItems();
  }

  protected function loadConfigItems() {
    $configItems = ConfigManager::loadConfigItems($this->getId(), $this->getConfig()->getId());

    $items = [];

    foreach ($configItems as $itemId => $itemConfig) {
      $class = $itemConfig['class'];

      if (strpos($class, '\\') === FALSE) {
        $namespace = '\\Drupal\\aluminum_storage\\Aluminum\\Config\\Item\\';
        $class = $namespace . $class . 'ConfigItem';
      }
      unset($itemConfig['class']);

      if (!class_exists($class)) {
        throw new ConfigException(sprintf("Specified class %s not found", $class));
      }

      $r = new ReflectionClass($class);

      $items[$itemId] = $r->newInstanceArgs([
        $itemId,
        $this,
        $itemConfig
      ]);
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigId() {
    return $this->config->getId();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigGroupId() {
    $parts = explode('.', $this->getId());

    return array_pop($parts);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigData() {
    $configData = $this->getConfig()->getConfigData();

    if (!isset($configData[$this->getConfigGroupId()])) {
      return [];
    }

    return $configData[$this->getConfigGroupId()];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigData($configData, $itemId = NULL) {
    if (!is_null($itemId)) {
      $configData = [$itemId => $configData] + $this->getConfigData();
    }

    return $this->getConfig()->setConfigData($configData, $this->getConfigGroupId());
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * {@inheritdoc}
   */
  public function hasConfigItems() {
    $configItems = $this->getConfigItems();

    return (!empty($configItems));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigItems() {
    return $this->configItems;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigItem($itemId) {
    if (!isset($this->configItems[$itemId])) {
      throw new ConfigException(sprintf("Config item %s not set.", $itemId));
    }

    return $this->configItems[$itemId];
  }


  /**
   * {@inheritdoc}
   */
  public function setConfigItem($itemId, ConfigItemInterface $configItem) {
    $this->configItems[$itemId] = $configItem;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormArray(FormStateInterface $formState = NULL) {
    $formArray = [
      '#title' => $this->getName(),
      '#weight' => $this->getWeight(),
      '#type' => 'details',
      '#group' => $this->getConfig()->getFormId(),
    ];

    foreach ($this->getConfigItems() as $itemId => $item) {
      $formArray[$itemId] = $item->getFieldArray($formState);
    }

    return $formArray;
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormData(FormStateInterface $formState, $save = TRUE) {
    $success = TRUE;

    foreach ($this->getConfigItems() as $itemId => $item) {
      $result = $item->submitFormData($formState, FALSE);

      if (!$result) {
        $success = FALSE;
      }
    }

    if ($success && $save) {
      $this->getConfig()->save();
    }

    return $success;
  }
}
