<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/8/15
 * Time: 4:56 PM
 *
 *
 * Used for validating forms
 **/
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
                           if($dbHandle->validationCheck($ruleValue, $value)){
                                self::addError("{$item} already exists.");
                            }
                            break;
                        case 'blueacorn':
                            $blueacorn = substr($value, -14);
                            if($blueacorn !== $ruleValue) {
                                self::addError(("Must have a Blue Acorn email address!"));
                            }
                        case 'exists':
                            $studentArray = explode(',', $value);
                            if(isset($studentArray)) {
                                foreach ($studentArray as $student) {
                                    if(!$dbHandle->validationCheck($ruleValue, $student)){
                                        self::addError('Student does not exists');
                                    }
                                }
                            }
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

    public static function checkBlueAcornEmail($email)
    {
        $blueacorn = substr($email, -14);
        if($blueacorn == '@blueacorn.com') {
            return true;
        }
        return false;
    }
}