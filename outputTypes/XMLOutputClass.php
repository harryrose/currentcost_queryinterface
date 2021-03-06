<?php
	
	class XMLOutputClass implements OutputClass
	{
		const DateFormat = 'c';

		public function GetMimeString()
		{
			return "text/xml";
		}

		public function BeginOutput()
		{
			echo "<?xml version=\"1.0\" ?>";
		}

		public function EndOutput(){}


		public function OutputSensorDatas($data)
		{
			echo "<sensordata>";
			foreach($data as $row)
			{
				echo "<data>";
					echo "<time>".$row->getTime()->format('c')."</time>";
					echo "<type>".$row->getType()."</type>";
					echo "<sensor>".$row->getSensorID()."</sensor>";
					echo "<value>".$row->getValue()."</value>";
				echo "</data>";
			}
			echo "</sensordata>";
		}

		public function OutputException($e)
		{
			echo "<exception>";
				echo "<status>".$e->GetStatus()."</status>";
				echo "<message>".htmlspecialchars($e->GetMessage())."</message>";
			echo "</exception>";
		}
	}

	global $outputClassRegister;
	$outputClassRegister->xml = "XMLOutputClass";
