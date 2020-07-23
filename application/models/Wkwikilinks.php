<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Wkwikilinks extends Application_Model_DomainObject 
{

    protected $id, $inventionId, $link, $linkFor;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $inventionId, $link, $linkFor)
    {
        parent::__construct($id, compact('inventionId', 'link', 'linkFor'));
    }
    
}

class Application_Model_WkwikilinksMapper extends Application_Model_Mapper
{
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Reactions($row->id, array($row->inventionId, $row->link, $row->linkFor));
    }
}