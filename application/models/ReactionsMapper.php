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
class Application_Model_ReactionsMapper extends Application_Model_Mapper {

    /**
     * Build method to return selected row records
     * 
     * @author Mujaffar added on 20 Feb 2013
     * @param Zend_Db_Table_Row $row
     */
    public function build(Zend_Db_Table_Row $row) {
        return new Application_Model_Reactions($row->id, array($row->element1, $row->element2, $row->product));
    }

    public function getRelations($product, $params) {
        $select = $this->getDbTable()->select();
        $select->where("product = ?", $product);
        if ($params['forproduct']) {
            $select->where("forproduct = ?", $params['forproduct']);
        }
        return $this->fetchAll($select, false);
    }

    public function checkRelations($elem1, $elem2) {
        $select = $this->getDbTable()->select();
        $select->where("element1 = ?", $elem1);
        $select->where("element2 = ?", $elem2);

        return $this->fetchAll($select, false);
    }

}
