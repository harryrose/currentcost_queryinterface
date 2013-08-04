<?php

	public class AggregationPeriods
	{
		const None   = new AggregationPeriods(0);
		const Second = new AggregationPeriods(1);
		const Minute = new AggregationPeriods(2);
		const Hour   = new AggregationPeriods(3);
		const Day    = new AggregationPeriods(4);
		const Month  = new AggregationPeriods(5);
		const Year   = new AggregationPeriods(6);

		private $value;

		private __construct($value)
		{
			$this->value = $value;
		}

		public __get($key) {
			return $this->value;
		}
	}

	public class QuerySelector
	{
		private $dataTypeEqualTo = null;

		private $dataValueGreaterThan = null;
		private $dataValueLessThan = null;
		private $dataValueEqualTo = null;

		private $sensorIdEqualTo = null;

		private $timeGreaterThan = null;
		private $timeLessThan = null;
		private $timeEqualTo = null;

		private $limit = null;
		private $skip = null;
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
			if(!$aggregation instanceof AggregationPeriods)
			{
				throw new Exception("Aggregation period '$aggregation' is not a valid aggregation period.");
			}
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
