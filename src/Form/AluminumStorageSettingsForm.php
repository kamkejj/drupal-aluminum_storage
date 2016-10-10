<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 9/10/2016
 * Time: 5:57 PM
 */

namespace Drupal\aluminum_storage\Form;


use Drupal\aluminum_storage\Aluminum\Config\ConfigInterface;
use Drupal\aluminum_storage\Aluminum\Config\ConfigManager;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for Aluminum
 */
abstract class AluminumStorageSettingsForm extends ConfigFormBase {
  // You MUST set this
  protected $configId;

  protected $config;

  protected function getConfigId() {
    return $this->configId;
  }

  /**
   * @return ConfigInterface
   */
  protected function aluminumStorageConfig() {
    if (!isset($this->config)) {
      $this->config = ConfigManager::getConfig($this->getConfigId());
    }

    return $this->config;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return $this->aluminumStorageConfig()->getFormId();
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['aluminum_storage.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form[$this->getFormId()] = $this->aluminumStorageConfig()->getFormArray($form_state);

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->aluminumStorageConfig()->submitFormData($form_state);

    parent::submitForm($form, $form_state);
  }
}
