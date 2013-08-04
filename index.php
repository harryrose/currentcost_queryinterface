<?php
	include_once('classes/Database.php');
	include_once('classes/OutputClass.php');
	include_once('classes/QuerySelectors.php');
	include_once('classes/QuerySelectorBuilder.php');
	include_once('classes/SensorData.php');

	$dbClassName = "Mongo";
	$querySelectorName = "default";

	$registeredOutputTypes = Array();

	function RegisterOutputType($typestring, $className)
	{
		global $registeredOutputTypes;
		$registeredOutputTypes[$typestring] = $className;
	}

	function GetOutputTypeInstance($typestring)
	{
		global $registeredOutputTypes;
		if(isset($registeredOutputTypes[$typestring]))
		{
			return new $registeredOutputTypes[$typeString];
		}
		return null;
	}

	$registeredQuerySelectorBuilders = Array();
	
	function RegisterQuerySelectorBuilder($idstring,$className)
	{
		global $registeredQuerySelectorBuilders;
		$registeredQuerySelectorBuilder[$idstring] = $className;
	}

	function GetQuerySelectorBuilerInstance($idstring)
	{
		global $registeredQuerySelectorBuilders;
		if(isset($registeredQuerySelectorBuilders[$idstring]))
			return new $registeredQuerySelectorBuilders[$idstring];
		else return null;
	}

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
		$outputType = GetOutputTypeInstance($_GET['out']);
	else
		throw new Exception("Please define an output type using get variable 'out'.");

	$querySelectorBuilder = GetQuerySelectorBuilderInstance($querySelectorName);

	$querySelector = $querySelectorBuilder->BuildQuerySelector();
	
	$mime = $outputType->GetMimeString();
	header("Content-type: $mime");
	
	$data = $database->GetData($querySelector);
	
	$outputType->BeginOutput();
	$outputType->OutputSensorDatas($data);
	$outputType->EndOutput();
?>
