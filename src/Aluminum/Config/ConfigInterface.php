<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/8/2016
 * Time: 5:13 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;
use Drupal\Core\Form\FormStateInterface;

/**
 * Interface ConfigInterface
 *
 * An interface representing an entire config tree for a config type.
 *
 * @package Drupal\aluminum_storage\Config
 */
interface ConfigInterface {
  /**
   * Gets the machine name of this config type.
   *
   * @return string The machine name of this config item
   */
  public function getId();

  /**
   * Gets the human-readable name of this config type, optionally
   * translated, for display.
   *
   * @param bool $translate Whether or not to return a pre-translated string
   * @return string The human-readable name of this config item
   */
  public function getName($translate = TRUE);

  /**
   * A shortcut function to directly retrieve a value from a group in this
   * config object.
   *
   * @param string $itemId The id of the configuration item to retrieve
   * @param string $groupId The group to retrieve the configuration item from
   * @param mixed $default The bare minimum to return if no config item is found
   * @return mixed The value to retrieve, or the default value.
   */
  public function getValue($itemId, $groupId = 'general', $default = NULL);

  /**
   * Returns all group objects for this config type keyed by group ID.
   *
   * @return ConfigGroupInterface[]
   */
  public function getConfigGroups();

  /**
   * Returns the raw config data for this configuration id
   *
   * @return array The config data retrieved
   */
  public function &getConfigData();

  /**
   * Sets new config data in storage.
   *
   * @param mixed $configData
   * @param string|NULL $groupId An optional group ID to set instead
   * @return bool TRUE if successful, FALSE otherwise
   */
  public function setConfigData($configData, $groupId = NULL);

  /**
   * Returns the ConfigGroupInterface for the group ID specified.
   *
   * @param $groupId string The machine name of the group to retrieve
   * @return ConfigGroupInterface The interface identified by $groupId
   */
  public function getConfigGroup($groupId);

  /**
   * Adds or overwrites the ConfigGroupInterface instance for a group ID.
   *
   * @param string $groupId The group ID to add or overwrite
   * @param ConfigGroupInterface $group The group object itself
   */
  public function setConfigGroup($groupId, ConfigGroupInterface $group);

  /**
   * Gets the ID to be used for the parent item of this configuration form.
   *
   * @return string
   */
  public function getFormId();

  /**
   * Returns a full render array for a configuration form for this config object.
   *
   * @param \Drupal\Core\Form\FormStateInterface|NULL $formState
   * @return array
   */
  public function getFormArray(FormStateInterface $formState = NULL);

  /**
   * Let the config object pass the form state to each config item in turn and
   * save the results.
   *
   * @param \Drupal\Core\Form\FormStateInterface $formState
   * @param bool $save
   * @return bool TRUE if succeeded, FALSE if failed
   */
  public function submitFormData(FormStateInterface $formState, $save = TRUE);

  /**
   * Saves current config data for this object to storage.
   *
   * @return bool TRUE on success, FALSE on failure
   */
  public function save();
}
