<?php
	interface SensorDatabase
	{
		public function GetData($QuerySelectors);
	}

	interface UserDatabase
	{
		public function AddUser($User);
		public function RemoveUser($UserOrUserID);
		public function GetUsers();
		public function GetUser($UserID);
		public function UserExists($UserOrUserID);
	}
?>
