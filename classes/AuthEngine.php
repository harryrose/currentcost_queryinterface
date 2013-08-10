<?php
	interface AuthEngine
	{
		public function Init($database);
		public function IsAuthorized();
		public function EmitHeaders();
		public function GetHelpString();
	}
	
	abstract class BaseAuthEngine implements AuthEngine
	{
		private $db;

		public function Init($db)
		{
			$this->db = $db;
		}

		protected function GetDB()
		{
			return $this->db;
		}
	}

	class NoAuthEngine extends BaseAuthEngine
	{
		public function IsAuthorized(){ return true; }
		public function EmitHeaders() { }
		public function GetHelpString() { return ""; }
	}
?>
