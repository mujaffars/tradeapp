<?php

// Include session controller file to set and get session variables
require_once 'SessionController.php';

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        ini_set('display_errors', '0');
        ////Zend_Session::destroy( true );
        /* Initialize action controller here */
        $this->_helper->_layout->setLayout('dashboard');
        $this->_helper->_layout->pageTitle = "";
        $this->_constants = Zend_Registry::get('constants');
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile('/js/comman_func.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/dom.jsPlumb-1.7.2-min.js?v=' . $this->_constants->key->jsversion);
        //$this->view->headScript()->appendFile('/js/main.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/md5.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/alche.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/jquery-ui-1.10.1.custom.min.js?v=' . $this->_constants->key->jsversion);
        //$this->view->headScript()->appendFile('/js/falld.js?v=' . $this->_constants->key->jsversion);
        $this->view->headLink()->appendStylesheet('/css/demo.css');
        $this->view->headLink()->appendStylesheet('/css/jsplumb.css');
        
        $objPer = new Application_Model_PersonsMapper();
        $allRec = $objPer->getAllRec();
        
        // Get all persons
        // $this->view->personList = Application_Model_Persons::mapper()->getAllRec();
    }
    
    public function indexBkupAction()
    {
        $this->view->headScript()->appendFile('/js/comman_func.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/dom.jsPlumb-1.7.2-min.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/main.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/jquery-ui-1.10.1.custom.min.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/falld.js?v=' . $this->_constants->key->jsversion);
        $this->view->headLink()->appendStylesheet('/css/demo.css');
        $this->view->headLink()->appendStylesheet('/css/jsplumb.css');

        // Get all persons
        $this->view->personList = Application_Model_Persons::mapper()->getAllRec();
    }

    public function newmethodAction()
    {
        echo "this is the new page";
    }
   

}
