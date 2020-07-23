<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initSession()
    {
        ini_set('display_errors', '0');
        Zend_Session::start();
    }

    protected function _initAttributeExOpenIDPath()
    {
        $autoLoader = Zend_Loader_Autoloader::getInstance();

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                'basePath' => APPLICATION_PATH,
                'namespace' => 'My_',
            ));

        $resourceLoader->addResourceType('openidextension', 'openid/extension/', 'OpenId_Extension');
        $autoLoader->pushAutoloader($resourceLoader);
    }

    protected function _initAppKeysToRegistry()
    {
        $appkeys = new Zend_Config_Ini(APPLICATION_PATH . '/configs/appkeys.ini');
        Zend_Registry::set('keys', $appkeys);

        // Code added to define constants
        $constants = new Zend_Config_Ini(APPLICATION_PATH . '/configs/constants.ini');
        Zend_Registry::set('constants', $constants);

        Zend_Registry::set('zrPerms', null);
    }

    protected function _initPlugins()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_Auth());
    }

    protected function _initUser()
    {
        // Check where auto-login enabled
        $constants = Zend_Registry::get('constants');
        
        if ($constants->key->autoLogin == 'true') { 
            $this->bootstrap('db');
            return Smapp::autoLogin();
        } else {
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $this->bootstrap('db');
                // Zend_Debug::dump($auth->getIdentity() );
                // if ($user = Users_Service_User::findOneByOpenId($auth->getIdentity())) {
                if ($user = Application_Model_User::Mapper()->buildFromOpenIdData($auth->getIdentity())) {
                    $userLastAccess = strtotime($user->lastaccess);
                    if ((time() - $userLastAccess) > 60 * 5) {
                        $date = new Zend_Date();
                        $user->lastaccess = $date->toString('YYYY-MM-dd HH:mm:ss');
                        $sessCnt = sizeof($_SESSION['mysession']);
                        $user->sessioncount = $sessCnt;
                        $user->save();
                    }
                    Smapp::setCurrentUser($user);
                }
            }
            return Smapp::getCurrentUser();
        }
    }

    /**
     * Initialized router to generate URL routers for different pages (e.g. Non-drill timecard report)
     * 
     * @author Mujaffar Sanadi    Created on 28 Jan 2013
     */
    public function _initRouter()
    {
        $frontcontroller = Zend_Controller_Front::getInstance();
        $router = $frontcontroller->getRouter();

        $router->addRoute('subfisystem', new Zend_Controller_Router_Route('subfisystem/', array('controller' => 'fisystem',
                'action' => 'subfisystem', 'module' => 'default')));

        // Phaselookup page
        $router->addRoute('phaselookup', new Zend_Controller_Router_Route('phaselookup', array('controller' => 'index',
                'action' => 'phaselookup', 'module' => 'default')));

        // Phaselookup page
        $router->addRoute('phaselookup2', new Zend_Controller_Router_Route('phaselookup/:moveAction/:date/:urlSltRigs/:urlSltDrills', array('controller' => 'index',
                'action' => 'phaselookup', 'module' => 'default')));

        // Rig map page
        $router->addRoute('rigmap', new Zend_Controller_Router_Route('rigmap', array('controller' => 'reports',
                'action' => 'rigmap', 'module' => 'default')));

        // Rig abbreviated listing page
        $router->addRoute('rigTracker', new Zend_Controller_Router_Route('rig-tracker', array('controller' => 'index',
                'action' => 'rig-tracker', 'module' => 'default')));

        // Rig abbreviated listing page
        $router->addRoute('rigTrackerWithParam', new Zend_Controller_Router_Route('rig-tracker/:sort/:order', array('controller' => 'index',
                'action' => 'rig-tracker', 'module' => 'default')));

        $route = new Zend_Controller_Router_Route(
                'support/', // The URL, after the baseUrl, with no params.
                array(
                    'module' => 'default',
                    'controller' => 'reports', // The controller to point to.
                    'action' => 'ticket'  // The action to point to, in said Controller.
                )
        );
        $frontcontroller->getRouter()->addRoute('support', $route);
    }

    protected function _initControllerHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/controllers/helpers');
    }

    protected function _initProfiler()
    {
        $constants = Zend_Registry::get('constants');
        
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity() && $constants->key->profiler) {
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
// create a new profiler
            $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
// enable profiling (this is only recommended in development mode, disable this in production mode)
            $profiler->setEnabled(true);
// add the profiler to the database object
            $db->setProfiler($profiler);
        }
    }

    /**
     * Function to generate metadata cache files of db-tables
     * 
     * @author  Mujaffar Sanadi Created on 03 Oct 2013
     */
    public function _initDbCache()
    {

        
    }

}

class Smapp extends Bootstrap
{

    /**
     * @var App_Model_User
     */
    protected static $_currentUser;

    public function __construct($application)
    {
        parent::__construct($application);
    }

    // public static function setCurrentUser(Users_Model_User $user)
    // {
    //     self::$_currentUser = $user;
    // }


    public static function setCurrentUser(Application_Model_User $user)
    {        
        self::$_currentUser = $user;
    }

    /**
     * @return App_Model_User
     */
    public static function getCurrentUser()
    {
        self::logwithuser();
        //joe@southeastdrilling.com
        return self::$_currentUser;
    }

    /**
     * @return App_Model_User
     */
    public static function getCurrentUserId()
    {
        // $user = self::getCurrentUser();
        // return $user->getId();
    }

    /**
     * Method to login with specific user 
     * 
     * Created by Mujaffar Sanadi  12 July 2013
     */
    public function logwithuser()
    {
        if (isset($_SESSION['lguser'])) {
            if (sizeof($_SESSION['lguser']) == 4):
                $identity = self::$_currentUser;
                // First check whether main login is of Admin user role
                if ($identity->role_id == 1) {
                    $identity->name = $_SESSION['lguser']['name'];
                    $identity->email = $_SESSION['lguser']['email'];
                    $identity->role_id = $_SESSION['lguser']['role_id'];
                    $identity->id = $_SESSION['lguser']['id'];
                    return $identity;
                }
            endif;
        }
    }

    /**
     * @return App_Model_User
     */
    public static function getOriginalUser()
    {
        $auth = Zend_Auth::getInstance();
        $orgUser = array();
        if ($auth->hasIdentity()) {
            if ($user = Application_Model_User::Mapper()->buildFromOpenIdData($auth->getIdentity())) { 
                $orgUser = array('id' => $user->id, 'name' => $user->name, 'role_id' => $user->role_id);
            }
        }
        return $orgUser;
    }

    /**
     * Method to autologin -- In case of Google auth not working
     * Useful for development purpose only
     * 
     * @author  Mujaffar Sanadi     Created on 30 Sep 2014
     */
    public static function autoLogin()
    {
        $auth = Zend_Auth::getInstance();
        
        if (!$auth->hasIdentity()) {
            // Get Kent user details from Users table
            $userDetails = Application_Model_User::Mapper()->findByEmail('kent@southeastdrilling.com');
            
            Smapp::setCurrentUser($userDetails);
            $auth = Zend_Auth::getInstance();
            $storage = $auth->getStorage();
            $data = array(
                'identity' => 'http://southeastdrilling.com/openid?id=108542457030991090474',
                'properties' =>
                array(
                    'firstName' => $userDetails->name,
                    'lastName' => $userDetails->last_name,
                    'email' => $userDetails->email
                )
            );
            $storage->write($data);
        }
        if ($auth->hasIdentity()) {
            if ($user = Application_Model_User::Mapper()->buildFromOpenIdData($auth->getIdentity())) {
                $userLastAccess = strtotime($user->lastaccess);
                if ((time() - $userLastAccess) > 60 * 5) {
                    $date = new Zend_Date();
                    $user->lastaccess = $date->toString('YYYY-MM-dd HH:mm:ss');
                    $sessCnt = sizeof($_SESSION['mysession']);
                    $user->sessioncount = $sessCnt;
                    $user->save();
                }
                Smapp::setCurrentUser($user);
            }
        }
        return Smapp::getCurrentUser();
    }

}