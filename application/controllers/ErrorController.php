<?php

class ErrorController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_layout->setLayout('bare');
    }

    public function indexAction()
    {
        $this->_helper->_layout->setLayout('dashboard');
        $this->view->header = 'Access denied';
        $this->view->message = 'You are not authorized to access this page.';
        if ($this->getRequest()->getParam('identity') == 'null'):
            $this->view->message = 'Your login session has expired, Please login again! <a href="/users/logout">login</a>';
        endif;

        // Error messages code for FISystem
        if ($this->getRequest()->getParam('from') == 'fisystem' || $this->getRequest()->getParam('from') == 'other'):
            $this->view->header = $this->getRequest()->getParam('header');
            $this->view->message = $this->getRequest()->getParam('message');
        endif;
    }

    public function errorAction()
    {
        $this->_helper->_layout->setLayout('dashboard');
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        $request_params = $errors->request->getParams();


        //Application_Model_ErrorLog::Mapper()->logError($errors->exception->getMessage(), $errors->exception->getTraceAsString(), $request_params['controller'], $request_params['action'], $request_params['module'], $dbFilePath);
        $this->view->request = $errors->request;
    }

    public function listLogAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}

