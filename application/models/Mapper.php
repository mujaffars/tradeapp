<?php

abstract class Application_Model_Mapper
{

    protected $_dbTable = null;
    protected $_targetDOClass, $_targetDbTableClass;

    public function __construct()
    {
        if (preg_match('/^Application_Model_(.+)Mapper$/', get_called_class(), $matches)) {
            $this->_targetDbTableClass = "Application_Model_DbTable_" . $matches[1];
            $this->_targetDOClass = "Application_Model_" . $matches[1];
            $this->_builderClass = $this->_targetDOClass . "Builder";
            if (!(class_exists($this->_targetDbTableClass) && class_exists($this->_targetDOClass) ))
                throw new Exception("Invalid Mapper/DO/DbTable Naming Convention");
            // $this->_dbTable = new $this->_targetDbTableClass();
        }
    }

    abstract protected function build(Zend_Db_Table_Row $row);

    public function getDbTable()
    {
        if (is_null($this->_dbTable))
            $this->_dbTable = new $this->_targetDbTableClass();
        return $this->_dbTable;
    }

    public function save(Application_Model_DomainObject $do)
    {
        if (!($do instanceof $this->_targetDOClass))
            throw new Exception("Invalid Domain Object");

        $fields = $do->getModifiedAttributes();
        if (count($fields) === 0)
            return;

        if (null === ($id = $do->getId())) {
            unset($fields['id']);
            $do->id = $this->getDbTable()->insert($do->get($fields));
        } elseif (in_array('id', $fields)) {
            $do->id = $this->getDbTable()->insert($do->get($fields));
        } else {

            $this->getDbTable()->update($do->get($fields) + array('modified' => new Zend_Db_Expr("NOW()")), array('id = ?' => $id));
        }
        $do->resetModifiedAttributes();
    }

    public function find($id)
    {
        $key = get_called_class() . "id$id";

        if (Zend_Registry::isRegistered($key))
            return Zend_Registry::get($key);

        $result = $this->getDbTable()->find($id);

        if (0 == count($result))
            return null;

        $obj = $this->build($result->current());


        Zend_Registry::set($key, $obj);

        return $obj;
    }

    // public function select(array $args = array() ){
    // extract($args);
// 		
// 		
    // $select = $this->getSelect();
//         
    // if(@is_array($where) ) 
    // foreach($where as $field=>$value)
    // $select->where($field, $value);
//         
    // if(@is_array($order) )
    // foreach($order as $field=>$direction)
    // $select->order($field . " " . $direction);
//             
    // return (isset($raw) && $raw==true ) ? $this->fetchRaw($select) : $this->fetchAll($select);
    // }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }

    public function fetchRaw(Zend_Db_Table_Select $select = null)
    {
        return $this->getDbTable()->fetchAll($select);
    }

    public function delete(Application_Model_DomainObject $do)
    {
        $where = $this->getDbTable()->getAdapter()->quoteInto('id = ?', $do->id);
        $this->getDbTable()->delete($where);
    }

    public function fetchAll(Zend_Db_Table_Select $select = null, $build = true)
    {
        $resultSet = $this->getDbTable()->fetchAll($select);
        if (!$build) {
            return $resultSet;
        }
        $entries = array();
        foreach ($resultSet as $row) {
            //$row->drills = array();
            $entries[] = $this->build($row);
        }
        return $entries;
    }

    public function deactivate(Application_Model_DomainObject $do)
    {
        $this->getDbTable()->update(array("active" => 0), array('id = ?' => $do->id));
    }

    public function reactivate(Application_Model_DomainObject $do)
    {
        $this->getDbTable()->update(array("active" => 1), array('id = ?' => $do->id));
    }

    /**
     * Function to enable equery profiler
     * 
     * @author  Mujaffar Sanadi     Created on 01 Oct 2013
     * @return  db object $db
     */
    public function enableProfiler()
    {
        $db = $this->getDbTable()->getAdapter();
        $db->getProfiler()->setEnabled(true);
        return $db;
    }

    /**
     * Function to show query profiler results
     * 
     * @author  Mujaffar Sanadi     Created on 01 Oct 2013
     * @param Profiler Object $db 
     */
    public function showProfiler($db)
    {
        Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQuery());
        Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getQueryParams());
        Zend_Debug::dump($db->getProfiler()->getLastQueryProfile()->getElapsedSecs());
        $db->getProfiler()->setEnabled(false);
        exit;
    }

}
