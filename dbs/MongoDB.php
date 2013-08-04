<?php
	class MongoDatabase implements Database
	{
		public function GetData($selector)
		{
			$m = new Mongo("mongodb://localhost");

			$db = $m -> sensordata;

			$collection = $db -> sensordata;

			// now time to build the query...

			$query = Array();

			if( $val = $selector->GetDataType() != null)
			{
				$query["type"] = (string) $val ;
			}

			if( $val = $selector->GetDataValueGreaterThan() != null)
			{
				$query["value"]['$gt'] = (double) $val;
			}

			if( $val = $selector->GetDataValueLessThan() != null)
			{
				$query["value"]['$lt'] = (double) $val;
			}

			if( $val = $selector->GetDataValueEqualTo() != null)
			{
				$query["value"] = (double) $val;
			}

			if( $val = $selector->GetTimeGreaterThan() != null)
			{
				$query["time"]['$gt'] = new MongoDate($val->getTimestamp());
			}

			if( $val = $selector->GetTimeLessThan() != null)
			{
				$query["time"]['$lt'] = new MongoDate((int)$val->getTimeStamp());
			}

			if( $val = $selector->GetTimeEqualTo() != null)
			{
				$query["time"] = new MongoDate((int)$val->getTimeStamp());
			}

			$limit = $selector->GetLimit();
			$skip = $selector->GetSkip();

			$cursor = $collection->find($query)->sort(array("time" => -1))->skip($skip)->limit($limit);

			$out = Array();

			while($cursor->hasNext())
			{
				$cursor->next();
				$row = $cursor->current();
				$dateTime = (new DateTime());
				$dateTime = $dateTime->setTimestamp($row["time"]->sec);
				array_push($out,new SensorData($row["type"],$dateTime,$row["sensor"],$row["value"]));
			}
		
			return $out;
		}

	}
?>
