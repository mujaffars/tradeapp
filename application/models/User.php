<?php

/**
 * User model db class for users table
 * 
 * @author Mujaffar created on 08 Jan 2012
 */
class Application_Model_User extends Application_Model_DomainObject {

    // Protected variables
    protected $name, $email, $lastaccess, $role_id, $sessioncount;

    /**
     * Construct method to define table fields
     * 
     * @param Integer $id
     * @param String $name
     * @param String $email
     * @param Integer $role_id
     * @param Date $lastaccess 
     */
    public function __construct($id = null, $name, $email, $role_id, $lastaccess=null, $sessioncount=0) {
        parent::__construct($id, compact('name', 'email', 'role_id', 'lastaccess', 'sessioncount'));
    }

}

