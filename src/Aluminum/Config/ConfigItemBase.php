<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 12:14 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;


use Drupal\aluminum\Aluminum\Traits\IdentifiableTrait;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;

abstract class ConfigItemBase implements ConfigItemInterface {
  use IdentifiableTrait;

  protected $id;

  protected $name = NULL;

  /** @var string An optional description to include in the field */
  protected $description = NULL;

  protected $multiple = FALSE;

  /** @var ConfigGroupInterface */
  protected $group;

  protected $defaultValue;

  protected $fieldArray = [];

  public function __construct($id, ConfigGroupInterface $group, $itemConfig = []) {
    $itemConfig += [
      'name' => NULL,
      'description' => NULL,
      'defaultValue' => NULL,
      'multiple' => FALSE,
      'fieldArray' => [],
    ];

    $this->id = $id;
    $this->group = $group;

    if (!empty($itemConfig['name'])) {
      $this->name = $itemConfig['name'];
    }

    if (!is_null($itemConfig['description'])) {
      $this->description = $itemConfig['description'];
    }

    $this->multiple = $itemConfig['multiple'];

    $this->defaultValue = $itemConfig['defaultValue'];
    $this->fieldArray = $itemConfig['fieldArray'] + $this->fieldArray;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigId() {
    return $this->group->getConfigId();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupId() {
    return $this->group->getId();
  }

  /**
   * {@inheritdoc}
   */
  public function getConfig() {
    return $this->group->getConfig();
  }

  /**
   * {@inheritdoc}
   */
  public function getGroup() {
    return $this->group;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigData() {
    $configData = $this->getGroup()->getConfigData();

    if (!isset($configData[$this->getId()])) {
      return NULL;
    }

    return $configData[$this->getId()];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigData($configData) {
    return $this->getGroup()->setConfigData($configData, $this->getId());
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldArray(FormState $formState = NULL) {
    return $this->buildFieldArray();
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultValue() {
    return $this->defaultValue;
  }

  /**
   * {@inheritdoc}
   */
  public function hasValue(FormStateInterface $formState = NULL) {
    if (!is_null($formState) && $this->formValueExists($formState)) {
      return TRUE;
    }

    $configData = $this->getConfigData();

    if (!is_null($configData)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue(FormStateInterface $formState = NULL) {
    if (!is_null($formState) && $this->formValueExists($formState)) {
      return $this->getFormValue($formState);
    }

    $configData = $this->getConfigData();

    if (!is_null($configData)) {
      return $configData;
    }

    return $this->getDefaultValue();
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($newValue, $save = TRUE) {
    $this->setConfigData($newValue);

    if ($save) {
      $this->getConfig()->save();
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormData(FormStateInterface $formState, $save = TRUE) {
    return $this->setValue($this->getFormValue($formState), $save);
  }

  /**
   * Gets the values for this item's field array.
   *
   * These values can be overridden by a specific field instance.
   *
   * Make sure to build on top of these defaults in a child class instead of
   * replacing them.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState Optional form state for current request
   * @return mixed
   */
  protected function buildFieldArray(FormStateInterface $formState = NULL) {
    $defaults = [
      '#type' => 'textfield',
      '#title' => $this->getName(),
      '#multiple' => $this->multiple,
      '#default_value' => $this->getValue($formState)
    ];

    if (!is_null($this->description)) {
      $defaults['#description'] = t($this->description);
    }

    return $this->fieldArray + $defaults;
  }

  /**
   * Extract the config value out of the form state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   * @return mixed The value to return from the form
   */
  protected abstract function getFormValue(FormStateInterface $formState);


  /**
   * Determine if a value for this item exists in the provided form data
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   * @return bool TRUE if the value exists in the form data (even if blank), FALSE otherwise
   */
  protected abstract function formValueExists(FormStateInterface $formState);
}
