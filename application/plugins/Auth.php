<?php

class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{

    private $_identity;

    /**
     * the acl object
     *
     * @var zend_acl
     */
    private $_acl;

    /**
     * the page to direct to if there is a current
     * user but they do not have permission to access
     * the resource
     *
     * @var array
     */
    private $_noacl = array('module' => 'admin',
        'controller' => 'error',
        'action' => 'index');

    /**
     * the page to direct to if there is not current user
     *
     * @var unknown_type
     */
    private $_noauth = array('module' => 'users',
        'controller' => 'auth',
        'action' => 'login');

    /**
     * validate the current user's request
     *
     * @param zend_controller_request $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        return true;
    }

    public function test()
    {
        $_identity = Smapp::getCurrentUser();
        if (empty($this->_identity)) {

            $temp = new Zend_Session_Namespace('temp');
            $temp->return_url = preg_replace('/^([^?]*)[?]?.*$/', '$1', $request->getRequestUri());
            $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $r->gotoUrl('/users/login')->redirectAndExit();
        }
        print_r($_identity);
        exit;
    }

    /**
     * post dispatch method called after action view loading is complete
     * 
     * @author Mujaffar added on 03 Oct 2013
     * @param Zend_Controller_Request_Abstract $request 
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $constants = Zend_Registry::get('constants');
        if ($constants->key->profiler) {
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $profiler = $db->getProfiler();
            $totalTime = $profiler->getTotalElapsedSecs();
            $queryCount = $profiler->getTotalNumQueries();
            $longestTime = 0;
            $longestQuery = null;

            foreach ($profiler->getQueryProfiles() as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }

            // Generate Html

            $postHtml = "<div style='background-color:white; clear:both'><div>Executed $queryCount queries in $totalTime seconds</div>
        <div>Average query length: $totalTime / $queryCount seconds</div>
        <div>Queries per second: $queryCount / $totalTime</div>
        <div>Longest query length: $longestTime</div>
        <div>Longest query: $longestQuery</div>
        <table class='table table-striped table-bordered' style='width:100%'>
            <tr>
            <th>detail</th>
            <th>elapsed sec</th>
        </tr>";

            foreach ($profiler->getQueryProfiles() as $query) {
                $postHtml.="<tr><td style='width: 600px;'>" . $query->getQuery() . "</td>
                <td>" . $query->getElapsedSecs() . "</td></tr>";
            }
            $postHtml.="</table></div>";

            if (!$this->getRequest()->isXmlHttpRequest()) {
                $this->getResponse()
                    ->appendBody($postHtml);
            }
        }
    }

}