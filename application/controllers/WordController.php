<?php

// Include session controller file to set and get session variables
require_once 'SessionController.php';

class WordController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('dashboard');
        $this->_helper->_layout->pageTitle = "";
        $this->_constants = Zend_Registry::get('constants');
    }

    public function indexAction()
    {
        $this->view->headScript()->appendFile('/js/comman_func.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/dom.jsPlumb-1.7.2-min.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/md5.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/word/init.js?v=' . $this->_constants->key->jsversion);
        $this->view->headScript()->appendFile('/js/jquery-ui-1.10.1.custom.min.js?v=' . $this->_constants->key->jsversion);
        $this->view->headLink()->appendStylesheet('/css/word/style.css');
        $this->view->headLink()->appendStylesheet('/css/word/jsplumb.css');
        
    }

}
