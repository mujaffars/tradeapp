<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Wkfacts extends Application_Model_DomainObject 
{

    protected $id, $invention_id, $fact;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $invention_id, $fact)
    {
        parent::__construct($id, compact('invention_id', 'fact'));
    }

}

class Application_Model_WkfactsMapper extends Application_Model_Mapper
{
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Reactions($row->id, array($row->invention_id, $row->fact));
    }
}