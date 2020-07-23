<?php

/**
 * User model db class for users table
 * 
 * @author Mujaffar created on 08 Jan 2012
 */
class Application_Model_Relation extends Application_Model_DomainObject {

    // Protected variables
    protected $id, $person_id_1, $relation_type, $person_id_2;

    /**
     * Construct method to define table fields
     * 
     * @param Integer $id
     * @param String $name
     * @param String $email
     * @param Integer $role_id
     * @param Date $lastaccess 
     */
    public function __construct($id = null, $person_id_1, $relation_type, $person_id_2) {
        parent::__construct($id, compact('person_id_1', 'relation_type', 'person_id_2'));
    }

}

