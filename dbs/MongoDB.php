<?php
	class MongoDatabase implements SensorDatabase, UserDatabase
	{
		private function GetDatabase()
		{
			$m = new Mongo("mongodb://localhost");
			return $m -> sensordata;
		}
		
		private function GetUserCollection()
		{
			return $this->GetDatabase()->users;
		}
		
		private function GetSensorDataCollection()
		{
			return $this->GetDatabase()->sensordata2;
		}

		public function AddUser($User)
		{
			if($this->UserExists($User))
				throw new UserExistsException($User->GetUsername());

			$users = $this->GetUserCollection();
			
			$user = array();

			$user["_id"] = $User->GetUsername();
			$user["password"] = $User->GetPassword();
			$user["salt"] = $User->GetSalt();

			$users->save($user);
		}

		public function RemoveUser($UserOrUserID)
		{
			// Don't implement this yet... No need for it.
		}

		public function GetUsers()
		{
			// TODO: For future
		}

		public function GetUser($UserID)
		{
			$users = $this->GetUserCollection();

			$userCursor = $users->find(array( "_id" => $UserID));

			if($userCursor->hasNext())
			{
				$userCursor->next();
				$row = $userCursor -> current();

				return new User($user["_id"],$user["password"],$user["salt"]);
			}
			else
			{
				throw new NoSuchUserException($UserID);
			}
		}

		public function UserExists($UserOrUserID)
		{
			$users = $this->GetUserCollection();
			$username = "";
			if($UserOrUserID instanceof User)
			{
				$username = $UserOrUserID->GetUsername();
			}
			else
			{
				$username = $UserOrUserID;
			}

			$userCursor = $users->find(array("_id" => $username));

			return $userCursor -> hasNext();
		}

		public function GetData($selector)
		{
			$collection = $this->GetSensorDataCollection();

			// now time to build the query...

			$query = Array();
		
			$tblPrefix = "value.";
			switch($selector->GetAggregation())
			{
				case AggregationPeriods::Minute:
					$collection = $collection -> minutely;
					break;

				case AggregationPeriods::Hour:
					$collection = $collection -> hourly;
					break;

				case AggregationPeriods::Day:
					$collection = $collection -> daily;
					break;

				case AggregationPeriods::Month:
					$collection = $collection -> month;
					break;
				
				default:
					$tblPrefix = '';
			}

			if(( $val = $selector->GetDataType() ) != null)
			{
				$query["_id.type"] = (string) $val ;
			}

			if(( $val = $selector->GetSensorIdEqualTo() ) != null)
			{
				$query["_id.sensor"] = (int) $val;
			}

			if(( $val = $selector->GetDataValueGreaterThan() ) != null)
			{
				$query[$tblPrefix."value"]['$gt'] = (double) $val;
			}

			if(( $val = $selector->GetDataValueLessThan() ) != null)
			{
				$query[$tblPrefix."value"]['$lt'] = (double) $val;
			}

			if(( $val = $selector->GetDataValueEqualTo() ) != null)
			{
				$query[$tblPrefix."value"] = (double) $val;
			}

			if(( $val = $selector->GetTimeGreaterThan() ) != null)
			{
				$query["_id.time"]['$gt'] = new MongoDate($val->getTimestamp());
			}

			if(( $val = $selector->GetTimeLessThan() ) != null)
			{
				$query["_id.time"]['$lt'] = new MongoDate((int)$val->getTimeStamp());
			}

			if(( $val = $selector->GetTimeEqualTo() ) != null)
			{
				$query["_id.time"] = new MongoDate((int)$val->getTimeStamp());
			}

			
			$limit = $selector->GetLimit();
			$skip = $selector->GetSkip();


			$cursor = $collection->find($query)->sort(array("_id.time" => -1))->skip($skip)->limit($limit);

			$out = Array();

			while($cursor->hasNext())
			{
				$cursor->next();
				$row = $cursor->current();
				//Map reduce...  There is probably a way around this.
				$value = $tblPrefix == '' ? $row["value"] : $row["value"]["value"];

				$dateTime = (new DateTime());
				$dateTime = $dateTime->setTimestamp($row["_id"]["time"]->sec);
				
				array_push($out,new SensorData($row["_id"]["type"],$dateTime,$row["_id"]["sensor"],$value));
			}
		
			return $out;
		}

	}
?>
