<?php

/**
 * class ValidationException
 * @package tcp.extensions.TCP.Exceptions
 */
class ValidationException extends TCPBaseException 
{
	private $_validationErrors;
	
	public function __construct($message, $errors)
	{
		$this->message = $message;
		$this->_validationErrors = $errors;
		
		$this->_data = array();
	}

	public function getErrors()
	{
		$errors = array();
		foreach ($this->_validationErrors as $attributeErrors) {
			if (is_array($attributeErrors)) {
				foreach ($attributeErrors as $e) {
					$errors[] = $e;
				}
			}
			else {
				$errors[] = $attributeErrors;
			}
		}
		return $errors;
	}
	
}
