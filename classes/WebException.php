<?php

	class WebException extends Exception
	{
		private $status = 400;

		public function __construct($status, $message, $code = 0, $cause = null)
		{
			parent::__construct($message,$code,$cause);

			$this->status = $status;
		}

		public function GetStatus()
		{
			return $this->status;
		}

		public function EmitStatus()
		{
			if(!defined('http_response_code'))
				header("HTTP/1.0 {$this->status}");
			else
				http_response_code($this->status);
	
		}

	}
?>
