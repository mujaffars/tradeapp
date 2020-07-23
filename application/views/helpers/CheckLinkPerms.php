<?php

/**
 * CheckLinkPerms View helper class to check specific link access w.r.t. user role
 * 
 * @author      Mujaffar Sanadi   Created on 10 May 2013 [Created public method checkLinkPerms]
 * @category    View Helper
 * @package     default
 * @copyright   SEDD
 */
class My_View_Helper_CheckLinkPerms extends Zend_View_Helper_Abstract
{

    /**
     * View helper method to return (true, false) boolean value for access permission
     * usefull for display / hide menu links in layout
     * 
     * @author Mujaffar added on 10 May 2013
     * @param String $phasetype    // Phase text
     * @return int $vphase_no  // Viewpoint phase no.
     * @access public
     */
    public function checkLinkPerms($module, $controller, $action)
    { 
        // Check atleast one action allowed from controller, So main controller menu link will be displayed
        $linkDefined = false;
        $arrPerms['allowMainLink'] = 0;
        foreach (Zend_Registry::get('zrPerms') AS $rowRegistry):
            if ($rowRegistry->module == $module && $rowRegistry->controller == $controller &&
                    ($rowRegistry->action == $action || $rowRegistry->action == 'all')) {
                if ($rowRegistry->access):
                    $arrPerms['allowSubLink'] = 1;
                else:
                    $arrPerms['allowSubLink'] = 0;
                endif;
            }
            if ($rowRegistry->module == $module && $rowRegistry->controller == $controller) {
                $linkDefined = true;
                if ($rowRegistry->access):
                    $arrPerms['allowMainLink'] = 1;
                endif;
            }
        endforeach;
        if (!$linkDefined)
            $arrPerms['allowMainLink'] = 1;
        // If permissions not set then return true
        return $arrPerms;
    }

}