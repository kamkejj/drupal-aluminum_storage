<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 1:56 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config\Item;


use Drupal\aluminum_storage\Aluminum\Config\ConfigItemBase;
use Drupal\Core\Form\FormState;

class DefaultConfigItem extends ConfigItemBase {

  /**
   * Extract the config value out of the form state.
   *
   * @param \Drupal\Core\Form\FormState $formState
   * @return mixed The value to return from the form
   */
  protected function getFormValue(FormState $formState) {
    return $formState->getValue($this->getId());
  }

  /**
   * Determine if a value for this item exists in the provided form data
   *
   * @param \Drupal\Core\Form\FormState $formState
   * @return bool TRUE if the value exists in the form data (even if blank), FALSE otherwise
   */
  protected function formValueExists(FormState $formState) {
    return $formState->hasValue($this->getId());
  }
}
