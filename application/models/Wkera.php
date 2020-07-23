<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Wkera extends Application_Model_DomainObject 
{

    protected $id, $first_name, $last_name, $dbo, $occupation, $native_place, $profile_img, $pet_name, $randomKey, $left, $top, $gender;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $name, $definedAs)
    {
        parent::__construct($id, compact('name', 'definedAs'));
    }
    
}

class Application_Model_WkeraMapper extends Application_Model_Mapper
{
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Reactions($row->id, array($row->name, $row->definedAs));
    }
}