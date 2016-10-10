<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/8/2016
 * Time: 5:18 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;
use Drupal\Core\Form\FormState;
use Drupal\Core\Form\FormStateInterface;

/**
 * Interface ConfigItemInterface
 *
 * Defines the minimum interface for an Aluminum Storage config item
 *
 * @package Drupal\aluminum_storage\Config
 */
interface ConfigItemInterface {
  /**
   * ConfigItemInterface constructor.
   *
   * @param string $id The id of this config item
   * @param ConfigGroupInterface $group The parent group object
   * @param array $itemConfig
   */
  public function __construct($id, ConfigGroupInterface $group, $itemConfig = []);

  /**
   * Gets the machine name of this config item.
   *
   * @return string The machine name of this config item
   */
  public function getId();

  /**
   * Gets the human-readable name of this config item, optionally
   * translated, for display.
   *
   * @param bool $translate Whether or not to return a pre-translated string
   * @return string The human-readable name of this config item
   */
  public function getName($translate = TRUE);

  /**
   * Gets the top level type of this config item.
   *
   * It must be one of: admin, content, appearance
   *
   * @return string The machine name of the config type
   */
  public function getConfigId();

  /**
   * Returns this config item's group machine name
   *
   * @return string The machine name of the parent group of this config item
   */
  public function getGroupId();

  /**
   * Gets the top level config object for this item.
   *
   * @return ConfigInterface The parent config object
   */
  public function getConfig();

  /**
   * Returns the parent group object of this config item
   *
   * @return ConfigGroupInterface The group object of this config item
   */
  public function getGroup();

  /**
   * Gets the raw config data for this item.
   *
   * @return mixed The config data for this item
   */
  public function getConfigData();

  /**
   * Sets new config data in storage.
   *
   * @param mixed $configData
   * @return bool TRUE if successful, FALSE otherwise
   */
  public function setConfigData($configData);

  /**
   * Gets the render array for this config item to be shown in a
   * settings form.
   *
   * @param \Drupal\Core\Form\FormState $formState An optional form state object for the current request
   * @return array The render array for this config item
   */
  public function getFieldArray(FormState $formState = NULL);

  /**
   * Gets the base default value for this config item.
   *
   * @return mixed The default value
   */
  public function getDefaultValue();

  /**
   * Gets the current value of this configuration field, falling back to
   * returning the default if not set. Uses any value set in $formState first.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   * @return mixed The current value of this configuration field
   */
  public function getValue(FormStateInterface $formState = NULL);

  /**
   * Sets a new value of this config item and saves it.
   *
   * @param $newValue mixed The raw context-dependent data to store
   * @param bool $save
   * @return mixed A boolean indicating whether the value was successfully set
   */
  public function setValue($newValue, $save = TRUE);

  /**
   * Extract the form data for this form item from the FormState object and
   * submit it.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   * @param bool $save
   * @return bool TRUE if succeeded, FALSE if failed
   */
  public function submitFormData(FormStateInterface $formState, $save = TRUE);
}
