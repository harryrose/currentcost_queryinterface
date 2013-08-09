<?php
	
	class JSONOutputClass implements OutputClass
	{
		const DateFormat = 'c';

		public function GetMimeString()
		{
			return "text/json";
		}

		public function BeginOutput()
		{
		}

		public function EndOutput(){}


		public function OutputSensorDatas($data)
		{
			$first = 1;
			echo "[";
			
			foreach($data as $row)
			{
				if(!$first) echo ",";
				$first = 0;
				echo "{";
					echo "\"time\": \"".$row->getTime()->format('c')."\",";
					echo "\"type\": \"".$row->getType()."\",";
					echo "\"sensor\": ".$row->getSensorID().",";
					echo "\"value\":".$row->getValue();
				echo "}";
			}
			echo "]";
		}

		public function OutputException($e)
		{
			echo "{ \"error\": ".$e->GetStatus().", \"message\": \"".addslashes($e->GetMessage())."\"}";
		}
	}

	global $outputClassRegister;
	$outputClassRegister->json = "JSONOutputClass";
