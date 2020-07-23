<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Wkinventions extends Application_Model_DomainObject 
{

    protected $id, $name, $eraId, $year, $inventorId, $imageName, $imageOffline, $imageLink;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $name, $eraId, $year, $inventorId, $imageName, $imageOffline, $imageLink)
    {
        parent::__construct($id, compact('name', 'eraId', 'year', 'inventorId', 'imageName', 'imageOffline', 'imageLink'));
    }
    
}

class Application_Model_WkinventionsMapper extends Application_Model_Mapper
{
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Reactions($row->id, array($row->name, $row->eraId, $row->year, $row->inventorId, $row->imageName, $row->imageOffline, $row->imageLink));
    }
}