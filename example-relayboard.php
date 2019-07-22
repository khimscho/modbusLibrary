<?php
// https://github.com/toggio/PhpSerialModbus
include("PhpSerialModbus.php");
include("FourPortModbusRelayBoard.class.php");

$relayHandler = new FourPortModbusRelayBoard("/dev/ttyUSB0", 7, false);
$relayHandler->allOff();
$relayHandler->relayOn(1);
sleep(3);
$relayHandler->relayOff(1);
$relayHandler->end();
?>
