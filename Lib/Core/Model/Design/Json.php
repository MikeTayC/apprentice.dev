<?php
class Core_Model_Design_Json
{
    private $_design = array();

    private $_jsonPathArray = array();

    private $_jsonDesignArray = array();

    public function setJsonDesign()
    {
        $jsonPaths = $this->setJsonPath();

        foreach ($jsonPaths as $jsonPath) {

            $this->_design = json_decode(file_get_contents($jsonPath), true);

            $this->_jsonDesignArray = array_merge_recursive($this->_design, $this->_jsonDesignArray);
        }
    }

    private function setJsonPath()
    {
        $jsonLibAppModules = glob('*/*/Design.json');

        $this->_jsonPathArray = $jsonLibAppModules;

        return $this->_jsonPathArray;
    }

    public function getJsonConfig()
    {
        return $this->_jsonDesignArray;
    }

    public function getLayoutActions()
    {
        return $this->_jsonDesignArray['layout']['actions'];
    }

    public function getActionHandle()
    {
        $layout = $this->getLayoutActions();;
        return array_keys($layout);
    }

    public function buildBlocks(){
        $layout = $this->getLayoutActions();
        return $this->buildBlock($layout['default']);

    }

    public function buildBlock($config){

        foreach($config as $nodeKey => $nodeValue){
            if($nodeKey === 'type' && is_string($nodeKey)){
                $block = Bootstrap::getView($nodeValue);
                continue;
            }
            elseif($nodeKey === 'template' && is_string($nodeKey)){
                $block->setTemplate($nodeValue);

                continue;
            }
            elseif(is_array($nodeValue)) {

                $block->setChild($nodeKey, $this->buildBlock($nodeValue));
                continue;
            }
        }

        return $block;
    }

//    public static function getActions()
//    {
//        return $this->$_jsonDesignArray['layout']['actions'];
//    }

//    public static function getDefaultTemplate(){
//        return $this->$_jsonDesignArray['layout']['actions']['default']['Template'];
//    }
//
//    public static function getDefaultFooterType(){
//        return $this->$_jsonDesignArray['layout']['actions']['default']['footer']['type'];
//    }
//
//    public static function getDefaultFooterTemplate(){
//        return $this->$_jsonDesignArray['layout']['actions']['default']['footer']['Template'];
//    }
//
//    public static function getDefaultHeaderType(){
//        return $this->$_jsonDesignArray['layout']['actions']['default']['header']['type'];
//    }
//
//    public static function getDefaultHeaderTemplate(){
//        return $this->$_jsonDesignArray['layout']['actions']['default']['header']['Template'];
//    }
//    public function getCurrentClassDesignJson($className)
//    {
//        $className = strtolower($className);
//        return $this->$_jsonDesignArray['layout']['actions'][$className];
//    }

}