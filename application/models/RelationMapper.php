<?php

/**
 * Model PhaseMapper class for handling phase table queries
 * 
 * @author  Mujaffar Sanadi, Created on 20 Feb 2013 [Created new methods build, findByDrillId, deactivate, markComplete, unmarkComplete, markPullComplete]
 * @author  Last Modified By : Mujaffar Sanadi on 25 Feb 2013,  [Added findByDrillIds method to get all phases of all drills in one query]
 * @author  Last Modified By : Mujaffar Sanadi on 05 May 2013,  [Added getvpPhaseNo and phaseLookup methods]
 * @package Default
 * @copyright SEDD
 */
class Application_Model_RelationMapper extends Application_Model_Mapper
{

    /**
     * Build method to return selected row records
     * 
     * @author Mujaffar added on 20 Feb 2013
     * @param Zend_Db_Table_Row $row
     */
    public function build(Zend_Db_Table_Row $row)
    {
        return new Application_Model_Persons($row->id, array($row->person_id_1, $row->relation_type, $row->person_id_2));
    }

    public function getAllRec()
    {
        $select = $this->getDbTable()->select();
        $select->order("id asc");
        return $this->fetchAll($select, false);
    }

    public function getRelations($personId)
    {
        $select = $this->getDbTable()->select();
        $select->where("person_id_1 = ?", $personId);

        return $this->fetchAll($select, false);
    }

    public function getAllRelations($personId)
    {
        $select = $this->getDbTable()->select();
        $select->setIntegrityCheck(false)
            ->from(array('Relation' => $this->getDbTable()->info('name')), array("*"))
            ->joinLeft(
                array('Person' => "persons"), "Person.id = Relation.person_id_1", array("Person.first_name", "Person.last_name", "Person.profile_img")
            )
            ->order("Person.id ASC");
        return $this->fetchAll($select, false);
    }

    public function delete($relation_id)
    {
        $this->getDbTable()->delete(array('id = ?' => $relation_id));
    }

}