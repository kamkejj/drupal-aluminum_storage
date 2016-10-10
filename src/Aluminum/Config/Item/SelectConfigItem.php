<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 10/9/2016
 * Time: 5:09 PM
 */

namespace Drupal\aluminum_storage\Aluminum\Config\Item;


use Drupal\aluminum_storage\Aluminum\Config\ConfigGroupInterface;
use Drupal\Core\Form\FormStateInterface;

class SelectConfigItem extends DefaultConfigItem {
  protected $options = [];

  protected $fieldArray = [
    '#type' => 'select',
  ];

  public function __construct($id, ConfigGroupInterface $group, $itemConfig = []) {
    parent::__construct($id, $group, $itemConfig);

    $itemConfig += ['options' => []];

    $this->options += $itemConfig['options'];
  }

  protected function buildFieldArray(FormStateInterface $formState = NULL) {
    $fieldArray = parent::buildFieldArray($formState);

    $fieldArray['#options'] = $this->options;

    return $fieldArray;
  }
}
