<?php
	
	class User
	{

		private $username;
		private $password;
		private $salt;
		
		public static function FromUsernamePassword($username,$rawPassword)
		{
			// generate a salt.
			$salt = base64_encode(openssl_random_pseudo_bytes(32));
			$password = User::HashPassword($rawPassword,$salt);

			return new User($username,$password,$salt);
		}

		public function __construct($Username, $SHA1Password, $Salt)
		{
			$this->username = $Username;
			$this->password = $SHA1Password;
			$this->salt = $Salt;
		}

		public function GetUsername()
		{
			return $this->username;
		}

		public function GetPasswordHash()
		{
			return $this->password;
		}

		public function GetSalt()
		{
			return $this->salt;
		}

		public function SetPassword($newPassword)
		{
			$this->password = User::HashPassword($newPassword,$this->salt);
		}

		public function IsCorrectPassword($password)
		{
			$testPassword = User::HashPassword($password,$this->salt);
			return $testPassword == $this->password;
		}

		private static function HashPassword($password,$salt)
		{
			return sha1($password.$salt);
		}
	}

	abstract class UserException extends Exception
	{
		private $username = "";
		public function __construct($username,$message)
		{
			parent::__construct($message);
			$this->username = $username;
		}

		public function GetUsername()
		{
			return $this->username;
		}
	}

	class UserExistsException extends UserException
	{
		public function __construct($username)
		{
			parent::__construct($username, "The user '$username' exists.");
		}
	}
	
	class NoSuchUserException extends UserException
	{
		public function __construct($username)
		{
			parent::__construct($username, "The user '$username' does not exist.");
		}
	}


?>
