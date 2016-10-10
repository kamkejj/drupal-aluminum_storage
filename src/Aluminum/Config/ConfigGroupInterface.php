<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/8/2016
 * Time: 5:15 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config;


use Drupal\Core\Form\FormStateInterface;

interface ConfigGroupInterface {
  /**
   * Gets the machine name of this config group object.
   *
   * @return string The machine name of this config group
   */
  public function getId();

  /**
   * Gets the config type ID of this config group.
   *
   * @return string The machine name of the config type this group belongs to
   */
  public function getConfigId();

  /**
   * Gets the human-readable name of this config group object, optionally
   * translated.
   *
   * @param bool $translate Whether or not to auto-translate the title
   * @return string The title of this config group
   */
  public function getName($translate = TRUE);

  /**
   * Gets the parent config object for this group.
   *
   * @return ConfigInterface The parent config object for this group
   */
  public function getConfig();

  /**
   * Gets the relative weight of this config group as an int.
   *
   * @return int
   */
  public function getWeight();

  /**
   * Gets the raw config data for this group.
   *
   * @return array The config data for this group
   */
  public function getConfigData();

  /**
   * Sets new config data in storage.
   *
   * @param mixed $configData
   * @param string|null $itemId An optional specific item ID to set instead
   * @return bool TRUE if successful, FALSE otherwise
   */
  public function setConfigData($configData, $itemId = NULL);

  /**
   * Get all config items in this group
   *
   * @return ConfigItemInterface[] All config items in this group
   */
  public function getConfigItems();

  /**
   * Gets a ConfigItemInterface object from this group
   *
   * @param string $itemId The machine name of the config item to retrieve
   * @return ConfigItemInterface The config item requested
   */
  public function getConfigItem($itemId);

  /**
   * Adds or overwrites a config item with the specified ConfigItemInterface
   * instance.
   *
   * @param string $itemId The ID to add this config item as
   * @param ConfigItemInterface $configItem The config item itself
   */
  public function setConfigItem($itemId, ConfigItemInterface $configItem);

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
}
