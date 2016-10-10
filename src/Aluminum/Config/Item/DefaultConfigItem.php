<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 1:56 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config\Item;


use Drupal\aluminum_storage\Aluminum\Config\ConfigItemBase;
use Drupal\Core\Form\FormStateInterface;

class DefaultConfigItem extends ConfigItemBase {

  /**
   * {@inheritdoc}
   */
  protected function getFormValue(FormStateInterface $formState) {
    return $formState->getValue($this->getId());
  }

  /**
   * {@inheritdoc}
   */
  protected function formValueExists(FormStateInterface $formState) {
    return $formState->hasValue($this->getId());
  }
}
