<?php 

namespace PayPal\Common;
//namespace PayPal;

//use PayPal\Api\Payer;
//require_once("protected/extensions/paypal/lib/PayPal/Api/Payer.php");


/**
 * Generic Model class that all API domain classes extend
 * Stores all member data in a hashmap that enables easy 
 * JSON encoding/decoding
 */
class Model {

	private $_propMap = array();	

	/**
	 * @var array|ReflectionMethod[]
	 */
	private static $propertiesRefl = array();
	
	/**
	 * @var array|string[]
	 */
	private static $propertiesType = array();
		
	public function __get($key) {
		return $this->_propMap[$key];
	}
	
	public function __set($key, $value) {
		$this->_propMap[$key] = $value;
	}
	
	public function __isset($key) {
		return isset($this->_propMap[$key]);
	}
	
	public function __unset($key) {
		unset($this->_propMap[$key]);
	}
	
	
	private function _convertToArray($param) {
		$ret = array();		
		foreach($param as $k => $v) {
			if($v instanceof Model ) {				
				$ret[$k] = $v->toArray();
			} else if (is_array($v)) {
				$ret[$k] = $this->_convertToArray($v);
			} else {
				$ret[$k] = $v;
			}
		}
		return $ret;
	}
	
	public function fromArray($arr) {
		
		foreach($arr as $k => $v) {
			if(is_array($v)) {
				$clazz = $this->getPropertyClass(get_class($this), $k);
				if(\ArrayUtil::isAssocArray($v)) {							
					$o = new $clazz();
					$o->fromArray($v);
					$setterFunc = "set".ucfirst($k);
					$this->$setterFunc($o);
				} else {
					$setterFunc = "set".ucfirst($k);
					$arr =  array();		
					foreach($v as $nk => $nv) {
						if(is_array($nv)) {
							$o = new $clazz();
							$o->fromArray($nv);
							$arr[$nk] = $o;
						} else {
							$arr[$nk] = $nv;
						}
					}
					$this->$setterFunc($arr);	//TODO: Cleaning up any current values in this case. Should be doing this allways
				} 
			}else {
				$this->$k = $v;
			}
		}
	}
	
	public function fromJson($json) {
		$this->fromArray(json_decode($json, true));
	}
	
	public function toArray() {
		return $this->_convertToArray($this->_propMap);
	}
	
	public function toJSON() {		
		return json_encode($this->toArray());
	}



	public function getPropertyClass($class, $propertyName) {
		
		if (($annotations = $this->propertyAnnotations($class, $propertyName)) && isset($annotations['param'])) {		
// 			if (substr($annotations['param'], -2) === '[]') {
// 				$param = substr($annotations['param'], 0, -2);
// 			}
			$param = $annotations['param'];
		}

		if(isset($param)) {
			$anno = explode(' ', $param);
			return $anno[0];
		} else {
			return 'string';
		}
	}



  
	public function propertyAnnotations($class, $propertyName)
	{
		$class = is_object($class) ? get_class($class) : $class;
		if (!class_exists('ReflectionProperty')) {
			throw new RuntimeException("Property type of " . $class . "::{$propertyName} cannot be resolved");
		}
	
		if ($annotations =& self::$propertiesType[$class][$propertyName]) {
			return $annotations;
		}
	
		$setterFunc = "set" . ucfirst($propertyName);
		if (!($refl =& self::$propertiesRefl[$class][$propertyName])) {
			$refl = new \ReflectionMethod($class, $setterFunc);
		}
	
		// todo: smarter regexp
		if (!preg_match_all('~\@([^\s@\(]+)[\t ]*(?:\(?([^\n@]+)\)?)?~i', $refl->getDocComment(), $annots, PREG_PATTERN_ORDER)) {
			return NULL;
		}
		foreach ($annots[1] as $i => $annot) {
			$annotations[strtolower($annot)] = empty($annots[2][$i]) ? TRUE : rtrim($annots[2][$i], " \t\n\r)");
		}
	
		return $annotations;
	}


}
