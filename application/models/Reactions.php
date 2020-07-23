<?php

/**
 * Model mapper class for Dashboard table
 * 
 * This mapper class handles queries on Dashboard table
 * 
 * @author Mujaffar added on 13 March 2013
 */
class Application_Model_Reactions extends Application_Model_DomainObject 
{

    protected $id, $product, $element1, $element2;
    
    /**
     * Build method to initialize variables
     * 
     * @author  Mujaffar Sanadi     Created on 13 March 2013
     * @param Zend_Db_Table_Row $row
     * @return Application_Model_Dashboard 
     */
    public function __construct($id = null, $product, $element1, $element2)
    {
        parent::__construct($id, compact('product', 'element1', 'element2'));
    }
    

}