<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/8/2016
 * Time: 8:49 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;


use Drupal\aluminum\Aluminum\Exception\AluminumException;
use Drupal\aluminum\Aluminum\Traits\IdentifiableTrait;
use Drupal\Core\Form\FormStateInterface;

class Config implements ConfigInterface {
  use IdentifiableTrait;

  protected $id;

  protected $name;

  protected $groups;

  protected $configData;

  public function __construct($id, $name = null) {
    $this->id = $id;
    $this->name = $name;
    $this->configData = $this->loadConfigData();
    $this->groups = $this->loadConfigGroups();
  }

  protected function loadConfigData() {
    $config = ConfigManager::loadConfigData();

    if (!isset($config[$this->getId()])) {
      return [];
    }

    return $config[$this->getId()];
  }

  protected function loadConfigGroups() {
    $configGroups = ConfigManager::loadConfigGroups($this->getId());

    $groups = [];

    foreach ($configGroups as $groupId => $groupConfig) {
      $groupId = $this->normalizeGroupId($groupId);

      $groups[$groupId] = new ConfigGroup(
        $groupId,
        $this,
        $groupConfig['name'],
        $groupConfig['weight']
      );
    }

    return $groups;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue($itemId, $groupId = 'general', $default = NULL) {
    $groupId = $this->normalizeGroupId($groupId);

    $group = $this->getConfigGroup($groupId);

    if (!is_a($group, '\\Drupal\\aluminum_storage\\Aluminum\\Config\\ConfigGroupInterface')) {
      return $default;
    }

    $item = $group->getConfigItem($itemId);

    if (!is_a($item, '\\Drupal\\aluminum_storage\\Aluminum\\Config\\ConfigItemInterface')) {
      return $default;
    }

    return $item->getValue();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigGroups() {
    return $this->groups;
  }

  /**
   * {@inheritdoc}
   */
  public function &getConfigData() {
    return $this->configData;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigData($configData, $groupId = NULL) {
    if (!is_null($groupId)) {
      $groupId = $this->normalizeGroupId($groupId);

      $configData = [$groupId => $configData] + $this->getConfigData();
    }

    $this->configData = $configData;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigGroup($groupId) {
    $groupId = $this->normalizeGroupId($groupId);

    if (!isset($this->groups[$groupId])) {
      $err = sprintf("Config group %s not found.", $groupId);

      throw new AluminumException($err);
    }

    return $this->groups[$groupId];
  }

  protected function normalizeGroupId($groupId) {
    $parts = explode('.', $groupId);

    return array_pop($parts);
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigGroup($groupId, ConfigGroupInterface $group) {
    $groupId = $this->normalizeGroupId($groupId);

    $this->groups[$groupId] = $group;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'aluminum_storage_form_' . $this->getId();
  }

  /**
   * {@inheritdoc}
   */
  public function getFormArray(FormStateInterface $formState = NULL) {
    $formArray = [];

    $formArray[$this->getFormId()] = [
      '#type' => 'vertical_tabs',
      '#title' => 'Aluminum settings: ' . $this->getName(),
    ];

    foreach ($this->getConfigGroups() as $groupId => $group) {
      if ($group->hasConfigItems()) {
        $formArray[$this->getFormId()][$group->getConfigGroupId()] = $group->getFormArray($formState);
      }
    }

    return $formArray;
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormData(FormStateInterface $formState, $save = TRUE) {
    $success = TRUE;

    foreach ($this->getConfigGroups() as $groupId => $group) {
      $result = $group->submitFormData($formState, FALSE);

      if (!$result) {
        $success = FALSE;
      }
    }

    if ($success && $save) {
      $this->save();
    }

    return $success;
  }

  /**
   * {@inheritdoc}
   */
  public function save() {
    return ConfigManager::saveConfigData($this->configData, $this->getId());
  }
}
