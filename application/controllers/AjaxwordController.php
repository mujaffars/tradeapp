<?php

class AjaxinvController extends Zend_Controller_Action
{

    protected $_constants;

        public function init()
    {
        $this->view->headScript()->appendFile('/js/jquery.validate.min.js');
        $this->view->headScript()->appendFile('/js/comman_func.js');
        /* Initialize action controller here */
        $this->_constants = Zend_Registry::get('constants');
    }

    public function addInventionAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->getRequest()->getParams();
        
        $fileContents = file_get_contents($params['images']);
        $imageName = str_replace("File:", "", basename($params['images']));
        
        $invention = new Application_Model_Wkinventions(
            null, $params['name'], "", $params['year'], "", $imageName, $params['images'], $params['images']);
        $invention->getMapper()->save($invention);

        // Insert facts for Invention
        $fact = new Application_Model_Wkfacts(
            null, $invention->id, $params['facts']);
        $fact->getMapper()->save($fact);
        
        file_put_contents('img/inv/' . $imageName, $fileContents);

        echo '<pre>';
        print_r($invention);
        echo '</pre>';
        exit;
    }

}
