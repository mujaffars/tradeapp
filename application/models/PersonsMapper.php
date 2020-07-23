<?php

/**
 * Model PhaseMapper class for handling phase table queries
 * 
 * @author  Mujaffar Sanadi, Created on 20 Feb 2013 [Created new methods build, findByDrillId, deactivate, markComplete, unmarkComplete, markPullComplete]
 * @author  Last Modified By : Mujaffar Sanadi on 25 Feb 2013,  [Added findByDrillIds method to get all phases of all drills in one query]
 * @author  Last Modified By : Mujaffar Sanadi on 05 May 2013,  [Added getvpPhaseNo and phaseLookup methods]
 * @package Default
 * @copyright SEDD
 */
class Application_Model_PersonsMapper extends Application_Model_Mapper
{

    /**
     * Build method to return currently selected row
     * 
     * @param   Zend_Db_Table_Row $row
     * @return  Application_Model_User 
     * @author  Unknown
     */
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Persons($row->id, array($row->first_name, $row->last_name, $row->dob, $row->occupation, $row->native_place,
                $row->profile_img, $row->pet_name, $row->randomKey, $row->left, $row->top, $row->gender));
    }

    public function getAllRec()
    {
        $select = $this->getDbTable()->select();
        return $this->fetchAll($select, false);
    }

    public function getLatestAdded($firstName, $lastName, $randomNo)
    {
        $select = $this->getDbTable()->select();
        $select->where("first_name = ?", $firstName)
            ->where("last_name = ?", $lastName)
            ->where("randomKey = ?", $randomNo);

        return $this->fetchAll($select, false);
    }

    public function getEditing($personId)
    {
        $select = $this->getDbTable()->select();
        $select->where("id = ?", $personId);
        return $this->fetchAll($select, false);
    }

    public function modifyPosition($params)
    {
        $this->getDbTable()->update(array('top'=>$params['top'], 'left'=>$params['left']), array('id = ?' => $params['personId']));
    }

    public function updateRecord($params)    { 
        $this->getDbTable()->update(array('first_name'=>$params['firstName'], 'last_name'=>$params['lastName'],
            'dob'=>$params['dob'], 'occupation'=>$params['occupation'], 'native_place'=>$params['nativePlace'],
            'profile_img'=>$params['profileImg'], 'pet_name'=>$params['petName'], 'gender' => $params['gender']), array('id = ?' => $params['personId']));
    }

}