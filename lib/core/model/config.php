<?php

/*
 * retrieves and merges json configuration files
 */
class Core_Model_Config
{
    public $config = array();

    public $jsonArray;

    public function getConfig()
    {
        $lists = glob('*/*/config.json');

        foreach($lists as $list)
        {
            $this->jsonArray = json_decode(file_get_contents($list), true);
            $this->config = array_merge_recursive($this->jsonArray, $this->config);
        }
        return $this->config;
    }
}