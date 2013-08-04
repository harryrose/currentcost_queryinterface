<?php
	interface QuerySelectorBuilder
	{
		public function BuildQuerySelector();
	}

	class DefaultQuerySelectorBuilder implements QuerySelectorBuilder
	{	
		public function BuildQuerySelector()
		{
			$qs = new QuerySelector();

			if(isset($_GET['type']))
				$qs->WithDataType($_GET['type']);

			if(isset($_GET['valuelt']))
				$qs->WithValueLessThan($_GET['valuelt']);

			if(isset($_GET['valuegt']))
				$qs->WithValueGreaterThan($_GET['valuegt']);

			if(isset($_GET['valueeq']))
				$qs->WithValueEqualTo($_GET['valueeq']);

			if(isset($_GET['sensorideq']))
				$qs->WithSensorIdEqualTo($_GET['sensorideq']);

			if(isset($_GET['timegt']))
				$qs->WithTimeGreaterThan(new DateTime($_GET['timegt']));

			if(isset($_GET['timelt']))
				$qs->WithTimeLessThan(new DateTime($_GET['timelt']));

			if(isset($_GET['timeeq']))
				$qs->WithTimeEqualTo(new DateTime($_GET['timeeq']));

			if(isset($_GET['limit']))
				$qs->WithLimit($_GET['limit']);

			if(isset($_GET['skip']))
				$qs->WithSkip($_GET['skip']);

			if(isset($_GET['aggregation']))
			{
				$aggregation = AggregationPeriods::None;

				switch(strtolower($_GET['aggregation']))
				{
					case 'none':
						$aggregation = AggregationPeriods::None;
					break;

					case 'second':
						$aggregation = AggregationPeriods::Second;
					break;

					case 'minute':
						$aggregation = AggregationPeriods::Minute;
					break;

					case 'hour':
						$aggregation = AggregationPeriods::Hour;
					break;

					case 'day':
						$aggregation = AggregationPeriods::Day;
					break;
				}

				$qs->WithAggregation($aggregation);
			}
			return $qs;
		}
	}

	global $querySelectorBuilderRegister;
	$querySelectorBuilderRegister->default = "DefaultQuerySelectorBuilder";
?>
