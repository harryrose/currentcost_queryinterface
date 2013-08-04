<?php
	error_reporting(E_ALL);

	$dbClassName = "MongoDatabase";
	$querySelectorBuilderName = "default";


	class ObjectRegister 
	{
		private $data;

		public function __construct()
		{
			$this->data = Array();
		}

		public function __set($key,$value)
		{
			$this->data[$key] = $value;
		}

		public function __get($key)
		{
			if(isset($this->data[$key]))
			{
				return new $this->data[$key];
			}
			else
			{
				return null;
			}
		}
	}

	$outputClassRegister = new ObjectRegister();
	$querySelectorBuilderRegister = new ObjectRegister();
	
	include_once('classes/Database.php');
	include_once('classes/OutputClass.php');
	include_once('classes/QuerySelectors.php');
	include_once('classes/QuerySelectorBuilder.php');
	include_once('classes/SensorData.php');
	
	function IncludeDirectory($dir)
	{
		foreach(glob("$dir/*.php") as $file)
		{
			include_once($file);
		}
	}

	IncludeDirectory("dbs");
	IncludeDirectory("outputTypes");

	$database = new $dbClassName;
	
	$outputType = null;
	if(isset($_GET['out']))
		$outputType = $outputClassRegister->$_GET['out'];
	else
		throw new Exception("Please define an output type using get variable 'out'.");

	$querySelectorBuilder = $querySelectorBuilderRegister->$querySelectorBuilderName;

	$querySelector = $querySelectorBuilder->BuildQuerySelector();
	
	$mime = $outputType->GetMimeString();
	header("Content-type: $mime");
	
	$data = $database->GetData($querySelector);
	
	$outputType->BeginOutput();
	$outputType->OutputSensorDatas($data);
	$outputType->EndOutput();
?>
