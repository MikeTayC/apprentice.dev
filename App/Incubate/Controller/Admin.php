<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 7/15/15
 * Time: 6:24 PM
 */
class Incubate_Controller_Admin extends Core_Controller_Admin
{
    /*
     * admin home
     */
    public function indexAction()
    {
        {
            $this->loadLayout();
            $this->render();
        }
    }

    public function newAction($param)
    {
        $view = $this->loadLayout();

        if(isset($param)) {
            $template = 'App/Incubate/View/Template/' . ucfirst($param) . '.phtml';
            $view->getContent()->setTemplate($template);
        }
        $view->render();
    }

    public function createAction($table)
    {
        if(isset($_POST)){
            Core_Model_Validator::check($_POST);
            if(Core_Model_Validator::passed()){
                if($table == 'user') {
                    $this->create($table, array(
                        'name' => Core_Helpers_Input::get('name'),
                        'email' => Core_Helpers_Input::get('email'),
                        'groups' => Core_Helpers_Input::get('groups'),
                        'joined' => date('Y-m-d H:i:s'),
                        'permission' => '1'

                    ));
                }
                elseif($table == 'lesson') {
                    $this->create($table, array(
                        'name' => Core_Helpers_Input::get('name'),
                        'desciption' => Core_Helpers_Input::get('description')
                    ));
                }
                elseif($table == 'teacher') {
                    $this->create('user', array(
                        'name' => Core_Helpers_Input::get('name'),
                        'email' => Core_Helpers_Input::get('email'),
                        'groups' => null,
                        'joined' => date('Y-m-d H:i:s'),
                        'permission' => !empty($_POST['permission']) ? '3' : '2'
                    ));
                }
            }
        }
        $this->redirect('Incubate', 'Admin' , 'indexAction');
    }


    public function studentsAction()
    {
        $view = $this->loadLayout();

        $users = Core_Model_Database::getInstance()->get('user', array('1', '=', '1'));

        $view->getContent()->setUsers($users->results());
        $view->getDefault()->setUser($this->user);

        $view->render();
    }
}
