<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/8/15
 * Time: 4:56 PM
 */
abstract class Core_Model_Validator
{
    public $_passed = false;
    public $_errors = array();
    public $_db = null;
    public $_validation = null;

    public function __construct()
    {
        $this->_db = Core_Model_Database::getInstance();
    }

    public function check($source)
    {
        $items = Core_Model_Config_Json::getValidationConfig();

        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $ruleValue) {

                $value = $source[$item];

                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $ruleValue) {
                                $this->addError("{$item} must be a minimum of {$ruleValue} characters.");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $ruleValue) {
                                $this->addError("{$item} must be a minimum of {$ruleValue} characters.");
                            }
                            break;
                        case 'matches' :
                            if ($value != $source[$ruleValue]) {
                                $this->addError("{$ruleValue} must match {$item}.");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($ruleValue, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            break;
                    }
                }
            }
        }
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
    }

    public function addError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}