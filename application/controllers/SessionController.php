<?php

class SessionController {

    public static $session;
    protected static $_instance;

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array()) {
        //parent::__construct($request, $response, $invokeArgs);
        $this->session = new Zend_Session_Namespace('mysession');

        return $this->session;
    }

    public static function getInstance($request, $response) {
        if (null === self::$_instance) {
            self::$_instance = new self($request, $response);
        }
        return self::$_instance;
    }

    public function getSessVar($var, $key, $default = null) {
        return (isset($this->session->$key->$var)) ? $this->session->$key->$var : $default;
    }

    public function setSessVar($var, $value, $key = null) {
        if (!empty($var) && !empty($value)) {
            $this->session->$key->$var = $value;
        }
    }

    public function destroySession() {
        Zend_Session::destroy(true);
    }

    public function destroySessionField($var, $key = null) {
        $this->session->$key->$var = "";
    }

}
