<?php

/**
 * View helper class to get constants from .ini file
 * 
 * @author  Mujaffar Sanadi     Created on 13 Oct 2014
 * @category  View helper
 * @package   Admin
 * @copyright SEDD
 * @filesource
 * @link
 */
class My_View_Helper_GetConstants extends Zend_View_Helper_Abstract
{

    /**
     * Function to get constants.ini file details
     * 
     * @author  Mujaffar Sanadi     Created on 13 Oct 2014
     * @return array Object    Zend_Registry 
     */
    public function getConstants()
    {
        return Zend_Registry::get('constants');
    }

}