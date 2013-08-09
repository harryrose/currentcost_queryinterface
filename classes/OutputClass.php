<?php

	interface OutputClass
	{
		public function GetMimeString();
		public function BeginOutput();
		public function EndOutput();
		public function OutputSensorDatas($sensorDataObjectArray);
		public function OutputException($exception);	
	}
?>
