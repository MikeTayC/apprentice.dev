<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/8/15
 * Time: 4:56 PM
 */
class Core_Model_Validator
{
    public static $_passed = false;
    public static $_errors = array();

    public static function check($source)
    {
        $dbHandle = Core_Model_Database::getInstance();
        $items = Core_Model_Config_Json::getValidationConfig();

        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $ruleValue) {

                $value = $source[$item];

                if ($rule === "required" && empty($value)) {
                    self::addError("{$item} is required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $ruleValue) {
                                self::addError("{$item} must be a minimum of {$ruleValue} characters.");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $ruleValue) {
                                self::addError("{$item} must be a minimum of {$ruleValue} characters.");
                            }
                            break;
                        case 'matches' :
                            if ($value != $source[$ruleValue]) {
                                self::addError("{$ruleValue} must match {$item}.");
                            }
                            break;
                        case 'unique':
                            $check = $dbHandle->get($ruleValue, array($item, '=', $value));
                            if($check->count()) {
                                self::addError("{$item} already exists.");
                            }
                            break;
                    }
                }
            }
        }
        if(empty(self::$_errors)) {
            self::$_passed = true;
        }
    }

    public static function addError($error)
    {
        self::$_errors[] = $error;
    }

    public static function errors()
    {
        return self::$_errors;
    }

    public static function passed()
    {
        return self::$_passed;
    }
}