<?php
	class SensorData
	{
		private $type = "";
		private $sensorid = 0;
		private $value = 0.0;
		private $time = null;

		public function __construct($type, $time, $sensorid, $value) 
		{
			$this->type = $type;
			
			if( $time instanceof DateTime )
				$this->time = $time;
			else
				$this->time = new DateTime($time);

			$this->value = $value;
			$this->sensorid = $sensorid;
		}

		public function getType()
		{
			return $this->type;
		}

		public function getSensorID()
		{
			return $this->sensorid;
		}

		public function getValue()
		{
			return $this->value;
		}

		public function getTime()
		{
			return $this->time;
		}
	}
?>
