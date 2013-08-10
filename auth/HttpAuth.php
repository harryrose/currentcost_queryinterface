<?php
	class HttpAuth extends BaseAuthEngine
	{
		public function IsAuthorized()
		{
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];

			if($this->GetDB()->UserExists($username))
			{
				$user = $this->GetDB()->GetUser($username);

				return $user->IsCorrectPassword($password);
			}

			return false;
		}

		public function EmitHeaders()
		{
			header('WWW-Authenticate: Basic realm=Sensors');
		}

		public function GetHelpString()
		{
			return "Please use HTTP authentication.";
		}
	}
?>
