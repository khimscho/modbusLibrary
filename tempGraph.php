<?php
echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>';
?>
<svg version="1.1" vxmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="800" height="400" style="background-color: #ededed;">
<style type="text/css">
.tempbar:hover {
	fill: #fccf03 !important;
}
</style>
<?php

$widthOfaBar = 4;

$file = file("/var/www/templog.log");
$recordsinfile = count($file);

$recordstoshow = array();
$min = 100;
$max = 0;
$pasthour = false;
$seenDegrees = array();

for ($i = $recordsinfile-200; $i < $recordsinfile ; $i++) {
	$logentry = $file[$i];
	$parts = explode("\t", $logentry);
	$date = $parts[0];
	$room = $parts[1];
	$temperature = $parts[2];

	if ($temperature < $min) {
		$min = $temperature;
	}

	if ($temperature > $max) {
		$max = $temperature;
	}

	$recordstoshow[] = array("hour" => date("H:i", strtotime($parts[0])), "date" => $parts[0], "room" => $parts[1], "temperature" => $parts[2]);

}

foreach ($recordstoshow as $counter => $logentry) {
	$recordstoshow[$counter]['temperaturecorrected'] = $logentry['temperature'];
	if ($pasthour != date("H", strtotime($logentry['date']))) {
		$recordstoshow[$counter]['newHour'] = true;
		$pasthour = date("H", strtotime($logentry['date']));
	}
	if (!in_array(round($logentry['temperature']), $seenDegrees)) {
		$seenDegrees[] = round($logentry['temperature']);
	}
}


foreach ($recordstoshow as $counter => $logentry) {
	if ($logentry['newHour']) {
		?><line x1="<?=($counter*$widthOfaBar)?>" y1="0" x2="<?=($counter*$widthOfaBar)?>" y2="400" style="stroke:rgb(100,100,100);stroke-width:1" stroke-dasharray="5,5" />
		<text x="<?=($counter*$widthOfaBar)?>" y="0" transform="rotate(90, <?=($counter*$widthOfaBar)?>,2)" font-family="Arial, Helvetica, sans-serif" font-size=10 fill="black"><?php
		if (substr($logentry['hour'], 0, 2) == "00") {
			?> <?=date("D d/m/Y", strtotime($logentry['date']))?><?php
		} else echo $logentry['hour'];
		?>
		</text>
		<?php
	}
	?><rect class="tempbar" x="<?=($counter*$widthOfaBar)?>" y="<?=400-trim($logentry['temperaturecorrected']*10)?>" width="<?=$widthOfaBar?>" height="<?=trim($logentry['temperaturecorrected']*10)?>"
  style="<?php
  	if ($counter%2 == 0) {
  		echo "fill:#d00";
  	} else {
  		echo "fill:#f00";
  	}
  ?>;stroke:none;"><title>temperature at <?=$logentry['hour']?>: <?=$logentry['temperature']?>&deg;C</title></rect>
  <?php

}

foreach ($seenDegrees as $degree) {
	?><line x1="0" y1="<?=400-($degree*10)?>" x2="800" y2="<?=400-($degree*10)?>" style="stroke:rgb(0,0,0);stroke-width:1" stroke-dasharray="1,2" />
	<text x="2" y="<?=400-($degree*10)-2?>" font-family="Arial, Helvetica, sans-serif" font-size=6 fill="black"><?=$degree?>&deg;C</text>
	<?php
}

?></svg>