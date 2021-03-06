<?php

/**
 * Class Core_Model_Object
 *
 * Contains magic functions crucial for framework function
 *
 * Stores data that can be set and get anywhere,
 * used heavily by models and view objects
 **/
class Core_Model_Object
{
    protected $_data = array();

    public function __call($methodName, $args)
    {
        if (substr($methodName, 0, 3) == 'set') {
            $key = $this->_uncamelize(substr($methodName, 3));
            $this->setData($key, $args[0]);
            return $this;
        } elseif (substr($methodName, 0, 3) == 'get') {
            $key = strtolower($this->_uncamelize(substr($methodName, 3)));
            return $this->getData($key);
        } else {
            return null;
        }

    }

    protected function _uncamelize($string = null)
    {
        if ($string) {
            return strtolower(preg_replace('/([A-Z])/', '_$1', lcfirst($string)));
        }
    }

    protected function _camelize($string = null)
    {
        if ($string) {
            $string = str_replace('_', ' ', $string);
            return str_replace(' ', '_', ucwords($string));
        }
        return false;
    }

    public function setData($key, $value)
    {
        if ($key) {
            $this->_data[$key] = $value;
        }
        return $this;
    }

    public function getData($key = false)
    {
        if ($key) {
            if (array_key_exists($key, $this->_data)) {
                return $this->_data[$key];
            } else {
                return null;
            }
        }
        return $this->_data;
    }
}
