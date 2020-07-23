<?php

/**
 * @file    Default Ajax controller for hanling ajax actions
 *
 * 
 * @author    Created By : Mujaffar Sanadi on 01 Aug 2014 [Created init, index actions]
 * @category  AjaxController.php
 * @package   Admin
 * @copyright SEDD
 * @filesource
 * @link
 */
class AjaxinvController extends Zend_Controller_Action
{

    /**
     * Application keys from constants.ini
     * 
     * @var Zend_Config 
     */
    protected $_constants;

    /**
     * Init method to initialize variable
     * This methods runs prior executing any action to initialize variables
     * 
     * @author Mujaffar Sanadi created on 01 Aug 2014
     */
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
