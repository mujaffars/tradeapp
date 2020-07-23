<?php



abstract class Application_Model_DomainObject{
    protected $id = null;
    // private $__reflection = null;
    protected $_modifiedfields = array(), $_modifiedSubObjects = array();

    public function __construct( $id = null, array $data = array() ){
    
		$this->id = $id;
        // $this->__reflection = new Zend_Reflection_Class( $this );
		$this->set($data);
	        if(!is_null($id))
	            $this->resetModifiedAttributes();
    }

    public function __get($key){
		return $this->get($key);
    }

    public function __set($k, $v){
		return $this->set($k, $v);
    }

    public function __toString(){
		if($this->hasProperty("name"))
		    return $this->name;

		return "";
    }
    
    public static function Mapper(){
        $classname = get_called_class() . "Mapper";
		return new $classname();

        $frontOpts = array('cached_entity'=> new $classname(), 'lifetime' => 60*5);
        $backOpts = array('cache_dir'=>APPLICATION_PATH . "/tmp");

        return Zend_Cache::factory("Class", "File", $frontOpts, $backOpts);
    }
    
    public function getMapper(){
    	return self::Mapper();
        $classname = get_called_class() . "Mapper";
        return new $classname();
    }
    
    public function removeFromModifiedFieldList($key){
        unset($this->_modifiedfields[$key]);
        return $this;
    }


    public function __call($name, $arguments){

	//Handle magic getters&setters&validator (get{Property}, set{Property}, validate{Property})
	if( preg_match("/^([gs]et|validate|has)([A-Z])([a-zA-Z0-9_]+)$/", $name, $matches )){
	    
	    $property = strtolower($matches[2]) . preg_replace("/([A-Z])/e", 'strtolower("_$1")', $matches[3] );

	    switch($matches[1]){
		case "get":
		    return $this->get($property);

		case "set":
		    return $this->set($property, $arguments[0]);

		case "has":
		    return $this->hasProperty($property) && !is_null( $this->get($property) );

		case "validate":
		    return $this->validate( $property, $arguments[0] );

	    }

	}

	throw new Application_Model_DomainObjectException("INVALID METHOD");
    }

    public function hasProperty($property){
		return $this->_getReflection()->hasProperty($property);
    }

    private function _getReflection(){
    	return new Zend_Reflection_Class($this);
    	// return $this->__reflection;
    }

    public function validate(){
	$args = func_get_args();

	switch( count($args) ){
	    case 1:
		if(!is_array($args[0]) )
		    return false;

		$validates = true;

		foreach($args[0] as $k=>$v)
        	    $validates = $validates && $this->validate($k, $v);

		return $validates;

	    case 2:
		return $this->__reflection->hasProperty($key) && preg_match($regexp, $this->$key);

	    default:
		return false;
	}
    }





    /**
     *@name get: wrapper for getter methods for flexible parameters
     *@example: $domainobject->get(array('id', 'name', 'description'));
     *@example: $domainobject->get('id');
     *@example: $domainobject->get('id,name,description');
     */

    public function get( $args ){

		switch( gettype($args) ){
	
		    case "string":
	
			//If comma separated, treat as array and return multiple properties
			if( strpos($args, ",")>0 )
			    return $this->get( array_map("trim", explode(",", $args)) );
	
			//If has dot, treat as getting object property
			if( strpos($args, ".")>0 ){
			    $parts = explode(".", $args);
			    $obj = $this->get( trim($parts[0]) );
	
			    for($i=1; $i<count($parts); $i++)
				$obj = ($obj instanceof self) ? $obj->get( trim($parts[$i]) ) : null;
	
			    return $obj;
	
			}
	
			//Assumes string is property name and returns value or null
			return ( $this->hasProperty($args) && !$this->isPrivateProperty($args) ) ? $this->$args : null;
	
			break;
	
		    case "array":
			$return_array = array();
			foreach($args as $k=>$v){
	
			    switch( gettype($v) ){
					case "array":
					    if( $this->hasProperty($k) && $this->$k instanceof self ){
						$value = $this->$k->get($v);
						if(!is_null($value) )
						    $return_array[$k] = $value;
					    }
					    break;
					case "string":
					    $value = $this->get( $v );
						$return_array[$v] = ($value instanceof self) ? $value->export() : $value;
					    break;
				    }
			}
	
			return $return_array;
	
			break;
	
		    default:
			return null;
		}
    }


    public function export(){
	foreach( $this->__reflection->getProperties() as $property )
	    $fields[] = $property->name;

	return $this->get($fields);

    }

    public function set(){
	$args = func_get_args();

	switch(count($args)){

	    //passed key-pair array for setting multiples at once.
	    case 1:

		if(is_array($args[0]))
		    foreach($args[0] as $k=>$v)
			$this->set($k, $v);
		break;

	    // Passed 2 parameters, 1st the attribute, 2nd the value (eg: $do->set("variable", "value"));
	    case 2:
		if($this->hasProperty($args[0]) && !$this->isPrivateProperty($args[0]) && $this->{$args[0]} !== $args[1]){
		    $this->{$args[0]} = $args[1];
                    
                    //mark to save (if not another object)
                    if(!(is_array($args[1]) || $args[1] instanceof self))
                        $this->_modifiedfields[ $args[0] ] = $args[0];
		}

		break;

	}

	return $this;

    }


    public function getModifiedAttributes(){
        return $this->_modifiedfields;
    }
    
    public function resetModifiedAttributes(){
        $this->_modifiedfields = array();
    }

    protected function resetModifiedSubObjects(){
    	$objs = $this->_modifiedSubObjects;
    	$this->_modifiedSubObjects = array();
    	return $objs;
    }

    protected function addModifiedSubObject(Application_Model_DomainObject $obj){
    	if(!is_null($obj->id))
	    	$this->_modifiedSubObjects[ get_class($obj) . ":" . $obj->id ] = $obj;
	    else
	    	$this->_modifiedSubObjects[ ] = $obj;

	    return $this;
    }

   

    private function isPrivateProperty($key){
	return false; //theres no secrets here
	return preg_match("/^_/", $key);
    }

    public function save(){
        $this->getMapper()->save($this);
        return $this;
    }

    public function delete(Application_Model_DomainObject $obj){
    	if($obj!==$this)
    		throw new Exception("Couldn't Delete Object");

    	$obj->getMapper()->delete($obj);
    }
    
    public function associateDomainObject($key, Application_Model_DomainObject $value = null){
        $regkey = get_called_class() . $this->id . $key;
        
        if( !is_null($value) )
            Zend_Registry::set($regkey, $value);
        
        return (Zend_Registry::isRegistered($regkey))?Zend_Registry::get($regkey): null;
        
    }
    
    public function hasAssociatedDomainObject($key){
        return ( !is_null($this->associateDomainObject($key)) );
    }


    

}



class Application_Model_DomainObjectException extends Exception{}

