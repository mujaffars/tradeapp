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
class AjaxController extends Zend_Controller_Action
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

    public function addPersonAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getRequest()->getParams();

        $this->view->personList = Application_Model_Persons::mapper()->getAllRec();

        if ($params['actionCase'] == 'SAVE') {

            $randNo = $this->_helper->Fisystem->generateRandomNo();

            $this->_helper->viewRenderer->setNoRender(true);

            $person = new Application_Model_Persons(
                null, $params['firstName'], $params['lastName'], $params['dob'], $params['occupation'], $params['nativePlace'], $params['profileImg'], $params['petName'], $randNo, 0, 0, $params['gender']
            );

            $person->getMapper()->save($person);

            // Get Record from persons table which is added latest
            $latestAddedRec = Application_Model_Persons::mapper()->getLatestAdded($params['firstName'], $params['lastName'], $randNo);

            // Create 3 relation records in Relations table
            /* $relation = new Application_Model_Relation(
              null,
              $latestAddedRec[0]['id'],
              'fatherOf',
              0
              );
              $relation->getMapper()->save($relation);

              $relation = new Application_Model_Relation(
              null,
              $latestAddedRec[0]['id'],
              'husbandOf',
              0
              );
              $relation->getMapper()->save($relation); */

            echo json_encode(array('status' => 'saved'));
        }
    }

    public function editPersonAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getRequest()->getParams();

        $this->view->editingPerson = Application_Model_Persons::mapper()->getEditing($params['personId']);

        if ($params['actionCase'] == 'Modify') {
            $this->_helper->viewRenderer->setNoRender(true);

            Application_Model_Persons::mapper()->updateRecord($params);
            echo json_encode(array('status' => 'modified'));
        }
    }

    public function makeRelationshipAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getRequest()->getParams();
        $this->view->personList = Application_Model_Persons::mapper()->getAllRec();

        if ($params['actionCase'] == 'SAVE') {
            $this->_helper->viewRenderer->setNoRender(true);

            $relation = new Application_Model_Relation(
                null, $params['person_id_1'], $params['relation_type'], $params['person_id_2']
            );

            $relation->getMapper()->save($relation);

            echo json_encode(array('status' => 'saved'));
        }
    }

    public function checkRelationshipAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->getRequest()->getParams();
        $this->view->personList = Application_Model_Persons::mapper()->getAllRec();
        $this->view->relationList = Application_Model_Relation::mapper()->getAllRelations();

        if ($params['actionCase'] == 'Delete') {
            $this->_helper->viewRenderer->setNoRender(true);
            Application_Model_Relation::Mapper()->delete($params['relation_id'], $drillId);
            echo json_encode(array('relation_id' => $params['relation_id'], 'status' => 'deleted'));
        }
    }

    public function modifyPositionAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->getRequest()->getParams();

        $latestAddedRec = Application_Model_Persons::mapper()->modifyPosition($params);
    }

    public function getReactionsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->getRequest()->getParams();

        $arrReactions = array();
        $recordReac = $this->getReac($params['element'], $params);

        $output = array('elem1' => $recordReac['element1'], 'elem2' => $recordReac['element2']);
        echo json_encode($output);
    }

    public function getReac($element, $params)
    {
        $recordReac = Application_Model_Reactions::mapper()->getRelations($element, $params);
        if (count($recordReac)) {
            return $recordReac[0];
        } else {
            return false;
        }
    }

    public function checkReactionsAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->getRequest()->getParams();

        $recordReac = Application_Model_Reactions::mapper()->checkRelations($params['elem1'], $params['elem2']);
        $output = array();
        $output['elem1'] = $params['elem1'];
        $output['elem2'] = $params['elem2'];
        if (count($recordReac)) {
            $output['found'] = 'true';
            $output['dtl'] = $recordReac[0];
            
            $output['product'] = $recordReac[0]->product;
            
            echo json_encode($output);
        } else {
            echo json_encode(array('found' => 'false'));
        }
    }

}
