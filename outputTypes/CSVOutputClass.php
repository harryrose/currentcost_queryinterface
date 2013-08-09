<?php
	
	class CSVOutputClass implements OutputClass
	{
		private $columns = Array("Time","SensorID","Type","Value");
		const DateFormat = 'c';

		public function GetMimeString()
		{
			return "text/csv";
		}

		public function BeginOutput()
		{
			$first = 1;
			foreach($this->columns as $col)
			{
				if(!$first) echo ",";
				$first = 0;

				echo "\"$col\"";
			}
			echo "\n";
		}

		public function EndOutput(){}

		public function OutputColumn($col,$data)
		{
			if($col == "Time")
			{
				echo '"'. $data->format(CSVOutputClass::DateFormat). '"';
			}
			elseif($col == "type")
			{
				echo '"'. $data .'"';
			}
			else
			{
				echo $data;
			}
		}

		public function OutputSensorDatas($data)
		{
			foreach($data as $row)
			{
				$first = 1;
				foreach($this->columns as $col)
				{
					if(!$first) echo ",";
					$first = 0;
					$method = "get$col";
					$this->OutputColumn($col,$row->$method());
				}

				echo "\n";
			}
		}

		public function OutputException($e)
		{
			echo $e->GetMessage();
		}
	}

	global $outputClassRegister;
	$outputClassRegister->csv = "CSVOutputClass";
