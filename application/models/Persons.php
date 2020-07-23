<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Persons extends Application_Model_DomainObject 
{

    protected $id, $first_name, $last_name, $dbo, $occupation, $native_place, $profile_img, $pet_name, $randomKey, $left, $top, $gender;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $first_name, $last_name, $dob, $occupation, $native_place, $profile_img, $pet_name, $randomKey, $left, $top, $gender)
    {
        parent::__construct($id, compact('first_name', 'last_name', 'dob', 'occupation', 'native_place', 'profile_img', 'pet_name', 'randomKey', 'left', 'top', 'gender'));
    }
    

}