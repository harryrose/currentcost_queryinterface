<?php

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
	
	$database = null;
	$outputType = null;
	
	function handle_shutdown()
	{
		global $outputType;
		$error = error_get_last();
		
		if($error == null)
			return;

		$ex = new WebException(500,$error['message']);
		$ex->EmitStatus();
		if($outputType != null)
			$outputType->OutputException($ex);
		else
		{
			header('Content-type: text/plain');
			echo $ex->GetMessage();
		}
		exit;
	}

	register_shutdown_function('handle_shutdown');

	include_once('classes/Database.php');
	include_once('classes/OutputClass.php');
	include_once('classes/QuerySelectors.php');
	include_once('classes/QuerySelectorBuilder.php');
	include_once('classes/SensorData.php');
	include_once('classes/WebException.php');

	function IncludeDirectory($dir)
	{
		foreach(glob("$dir/*.php") as $file)
		{
			include_once($file);
		}
	}



	try
	{
		IncludeDirectory("dbs");
		IncludeDirectory("outputTypes");

		$database = new $dbClassName;
	
		if(isset($_GET['out']))
			$outputType = $outputClassRegister->$_GET['out'];
		else
			throw new WebException(400,"Please define an output type using get variable 'out'.");
		
		if($outputType == null)
			throw new WebException(400,"The output type '{$_GET['out']}' is undefined.");

		
		try
		{
			$querySelectorBuilder = $querySelectorBuilderRegister->$querySelectorBuilderName;
			
			if($querySelectorBuilder == null)
				throw new WebException(400,"The query interpreter '$querySelectorBuilderName' is undefined.");
				
			$querySelector = $querySelectorBuilder->BuildQuerySelector();
	
			$mime = $outputType->GetMimeString();
			header("Content-type: $mime");
	
			$data = $database->GetData($querySelector);
	
			$outputType->BeginOutput();
			$outputType->OutputSensorDatas($data);
			$outputType->EndOutput();
		}
		catch(WebException $e)
		{
			$e->EmitStatus();
			$outputType->OutputException($e);
		}
	}
	catch(WebException $e)
	{
		$e->EmitStatus();
		header("Content-type: text/plain");
		echo $e->GetMessage();
	}
?>
