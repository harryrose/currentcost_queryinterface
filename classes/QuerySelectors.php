<?php

	class AggregationPeriods
	{
		const None   = 0;
		const Second = 1;
		const Minute = 2;
		const Hour   = 3;
		const Day    = 4;
		const Month  = 5;
		const Year   = 6;
	}

	class QuerySelector
	{
		private $dataTypeEqualTo = null;

		private $dataValueGreaterThan = null;
		private $dataValueLessThan = null;
		private $dataValueEqualTo = null;

		private $sensorIdEqualTo = null;

		private $timeGreaterThan = null;
		private $timeLessThan = null;
		private $timeEqualTo = null;

		private $limit = 0;
		private $skip = 0;
		private $aggregation = AggregationPeriods::None;

		public function WithDataType($type)
		{
			$this->dataTypeEqualTo = $type;
			return $this;
		}

		public function WithDataValueGreaterThan($value)
		{
			$this->dataValueGreaterThan = $value;
			return $this;
		}

		public function WithDataValueLessThan($value)
		{
			$this->dataValueLessThan = $value;
			return $this;
		}

		public function WithDataValueEqualTo($value)
		{
			$this->dataValueEqualTo = $value;
			return $this;
		}

		public function WithSensorIDEqualTo($value)
		{
			$this->sensorIdEqualTo = $value;
			return $this;
		}

		private function SetTime($variable, $time)
		{
			if($time instanceof DateTime)
			{
				$this->$variable = $time;
			}
			else
			{
				$this->$variable = new DateTime($time);
			}
		}

		public function WithTimeGreaterThan($time)
		{
			$this->SetTime("timeGreaterThan",$time);
			return $this;
		}

		public function WithTimeLessThan($time)
		{
			$this->SetTime("timeLessThan",$time);
			return $this;
		}

		public function WithTimeEqualTo($time)
		{
			$this->SetTime("timeEqualTo",$time);
			return $this;
		}

		public function WithLimit($limit)
		{
			$this->limit = $limit;
			return $this;
		}

		public function WithSkip($skip)
		{
			$this->skip = $skip;
			return $this;
		}

		public function WithAggregation($aggregation)
		{
			$this->aggregation = $aggregation;
		}


		public function GetDataType()
		{
			return $this->dataTypeEqualTo;
		}

		public function GetDataValueGreaterThan()
		{
			return $this->dataValueGreaterThan;
		}

		public function GetDataValueLessThan()
		{
			return $this->dataValueLessThan;
		}

		public function GetDataValueEqualTo()
		{
			return $this->dataValueEqualTo;
		}

		public function GetSensorIDEqualTo()
		{
			return $this->sensorIdEqualTo;
		}

		public function GetTimeGreaterThan()
		{
			return $this->timeGreaterThan;
		}

		public function GetTimeLessThan()
		{
			return $this->timeLessThan;
		}

		public function GetTimeEqualTo()
		{
			return $this->timeEqualTo;
		}

		public function GetLimit()
		{
			return $this->limit;
		}

		public function GetSkip()
		{
			return $this->skip;
		}

		public function GetAggregation()
		{
			return $this->aggregation;
		}
	}
		

?>
