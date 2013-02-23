<?php

/**
 * class AjaxResponse
 */
class AjaxResponse
{
	private $status;
	private $messages;
	private $data;

	public function __construct()
	{
		$this->status = false;
		$this->messages = array();
		$this->data = array();
	}

	public function setStatus($status, $message = '')
	{
		if ($status === true || $status === false) {
			$this->status = $status;

			if (!empty($message)) {
				if (is_array($message)) {
					$this->messages = array_merge($this->messages, $message);
				}
				else {
					$this->addMessage($message);
				}
			}
		}
		else {
			throw new Exception("Invalid status for this Response");
		}
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getMessages()
	{
		return $this->messages;
	}
	
	public function getMessagesAsString($deliminator = ' -- ')
	{
		$message = '';
		foreach ($this->messages as $m) {
			$message .= $m .$deliminator;
		}
		$len = strlen($deliminator);
		return substr($message, 0 , -$len);
	}

	public function addMessage($message)
	{
		$this->messages[] = $message;
	}

	public function addMessages($messages)
	{
		foreach($messages as $message)
			$this->addMessage($message);
	}

	public function asJson()
	{
		$result = (object) array(
			'status' => $this->status,
			'messages' => $this->messages,
			'data' => $this->data
		);

		return json_encode($result);
	}

	public function asObject()
	{
		$result = (object) array(
			'status' => $this->status,
			'messages' => $this->messages,
			'data' => (object) $this->data
		);

		return $result;
	}

	public function getData($key)
	{
		return $this->data[$key];
	}
	
	public function addData($key, $data)
	{
		$this->data[$key] = $data;
	}	

}
