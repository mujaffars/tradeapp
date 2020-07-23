<?php

/**
 * User Mapper class for executing queries
 * 
 * @author  Unknown
 * @author  Mujaffar Sanadi Modified on 05 Aug 2013 [Added new method getActive]
 */
class Application_Model_UserMapper extends Application_Model_Mapper
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
        return new Application_Model_User($row->id, $row->name, $row->email, $row->role_id, $row->lastaccess, $row->sessioncount);
    }

    /**
     * Method to return User record by compairing email address
     * 
     * @param   type $email
     * @return  type 
     * @author  Unknown
     */
    public function findByEmail($email)
    {
        $results = $this->fetchAll($this->getSelect()->where('email = ?', $email));
        return @$results[0];
    }

    /**
     * Method to build open Id record 
     * @param   array $data
     * @return  Application_Model_User 
     * @author  Unknown
     */
    public function buildFromOpenIdData(array $data)
    {
        if (!isset($data['identity'], $data['properties']))
            throw new Exception("Invalid User Data");

        extract($data['properties']);
        $user = $this->findByEmail($email);
        if ($user)
            return $user;
        $user = new Application_Model_User(null, $firstName . " " . $lastName, $email);
        $user->save();
        return $user;
    }

    /**
     * Method to fetch user record excepting provided user id
     * 
     * @param   Integer $userId
     * @param   String $email
     * @return  Array $rwsults 
     * @author  Unknown
     */
    public function findByEmailAndId($userId, $email)
    {
        $results = $this->fetchAll($this->getSelect()->where('email = ?', $email)->where('id != ?', $userId));
        return @$results[0];
    }

    /**
     * Getactive method to get active Users records
     * 
     * @author Mujaffar Sanadi added on 05 Aug 2013
     * @param Zend_Db_Table_Row $row
     */
    public function getActive()
    {
        $select = $this->getDbTable()->select();
        $select->order("name asc");
        return $this->fetchAll($select);
    }

    /**
     * Method to get Foreman Id related User details
     * 
     * @author Mujaffar Sanadi added on 21 Nov 2014
     * @param array $foremanIds
     * @return resultset 
     */
    public function getSelective($foremanIds)
    {
        $select = $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
            ->from(array('User' => $this->getDbTable()->info('name')), array("*"))
            ->joinLeft(
                array('Foremen' => "foremen"), "Foremen.email = User.email", array()
            )
            ->where("Foremen.id IN (?)", $foremanIds);
        return $this->fetchAll($select);
    }

    /**
     * Method to get superintendent related user record details
     * 
     * @author Mujaffar Sanadi added on 21 Nov 2014
     * @param type $userId
     * @return type 
     */
    public function getSuperiDtls($userId){
        $select = $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
            ->from(array('User' => $this->getDbTable()->info('name')), array("*"))
            ->joinLeft(
                array('Super' => "superintendent"), "Super.email = User.email", array('Super.id As superitendent_id')
            )
            ->where("User.id = (?)", $userId);
        
        return $this->fetchAll($select, false);
    }
}