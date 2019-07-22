#!/usr/bin/php
<?php
// print_r($argv);
// https://github.com/toggio/PhpSerialModbus
include("modbustests/PhpSerialModbus.php");
include("modbustests/FourPortModbusRelayBoard.class.php");

$relayHandler = new FourPortModbusRelayBoard("/dev/ttyUSB0", 7, true);
// $relayHandler->allOff();
if ($argv[1] == "garden") {
	if ($argv[2] == "on") {
	$relayHandler->relayOn(1);
	}

	if ($argv[2] == "off") {
	$relayHandler->relayOff(1);
	}
}

if ($argv[1] == "pavillon") {
	if ($argv[2] == "on") {
	$relayHandler->relayOn(2);
	}

	if ($argv[2] == "off") {
	$relayHandler->relayOff(2);
	}
}

// $relayHandler->relayOn(1);
// sleep(3);
// $relayHandler->relayOff(1);
// $relayHandler->end();
?>
