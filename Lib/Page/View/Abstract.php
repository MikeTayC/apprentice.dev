<?php

/**
 * Class Page_View_Abstract
 *
 * Abstract class extended by all view objects
 * Has ecessary and required functions for all view objects
 **/
abstract class Page_View_Abstract extends Core_Model_Object
{

    /** @var array refference to assets(css/js..) */
    protected $_assets = array();
    protected $_returnAssetsArray = array();

    /**
     * Function renders the template, finds the 'template' key in its _data
     * will be path to a phtml/html file
     **/
    public function render()
    {
        foreach ($this->_data as $nodeKey => $nodeValue) {
            if ($nodeKey === 'template') {
                $template = $nodeValue;
                if ($template && file_exists($template)) {
                    include $template;
                }
            }
        }
    }

    /**
     * Magic Function in case template does not exists in _data
     * @return string
     **/
    public function __toString(){
        if($this->getTemplate() && file_exists($this->getTemplate())){
                include $this->getTemplate();
        }
        return '';
    }

    /**
     * Retrieves all assets information saved in data, including
     * css,js and jquery
     **/
    public function getAssets(){
        $assets = $this->sortAssets();
        foreach($assets as $nodeKey => $nodeValue) {
            if ($nodeValue['type'] === 'css') {
                echo "<link href='" . '/assets/css/' . $nodeValue['file'] . "' rel='stylesheet'>" . "\n";
            } elseif ($nodeValue['type'] === 'js') {
                echo "<script src='" . '/assets/' . $nodeValue['file'] . "'></script>" . "\n";
            } elseif ($nodeValue['type'] === 'jquery') {
                echo "<script src='" . $nodeValue['file'] . "'></script>" . "\n";
            };
        }
    }

    /**
     * Sorts the assets
     * @return array
     **/
    public function sortAssets(){
        for($i=0; $i<count($this->_data['assets']); $i++)
            foreach ($this->_data['assets'][$i] as $node) {
                array_push($this->_assets, $node);
        }
        return $this->_assets;
    }
}