<?php

abstract class Core_Controller_Abstract
{
    protected $_crudModel;
    public function loadLayout($default = true)
    {
        $test = Bootstrap::getModel('page/design_json');
        $test->setJsonDesign();
        $layoutHandle = $this->getHandle();
        $block = $test->buildBlocks($layoutHandle, $default);

        $block->render();
    }

    private function getHandle()
    {
        $request = Core_Model_Request::getInstance();
        $layoutHandle = strtolower($request->getModule() . '_' . $request->getController() . '_' . substr($request->getAction(), 0, -6));
        return $layoutHandle;
    }

    public function getCrudModel()
    {
        $this->_crudModel = new Core_Model_Database_Crud();
    }

    public function create()
    {
        $this->getCrudModel();
        $this->_crudModel->create();
    }
    public function read($id)
    {
        $this->getCrudModel();
        return $this->_crudModel->read($id);
    }

    public function update($id)
    {
        $this->getCrudModel();
        $this->_crudModel->update($id);
    }

    public function delete($id)
    {
        $this->getCrudModel();
        $this->_crudModel->delete($id);
    }

}