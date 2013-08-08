<?php
	class MongoDatabase implements Database
	{
		public function GetData($selector)
		{
			$m = new Mongo("mongodb://localhost");

			$db = $m -> sensordata;

			$collection = $db -> sensordata2;

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
