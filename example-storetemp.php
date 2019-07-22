<?php
include("PhpSerialModbus.php");
$modbus = new PhpSerialModbus;
$modbus->debug = false;
$modbus->deviceInit('/dev/ttyUSB0',9600,'none',8,1,'none');
$modbus->deviceOpen();

// 8 = office
// temp
$result = $modbus->sendQuery(1,4,"0001",1);
error_log(date("c")."\tpavillon\t".(hexdec($result[0].$result[1])/10)."\n", "3", "/root/templog.log");

// humidity
$result = $modbus->sendQuery(1,4,"0002",1);
error_log(date("c")."\tpavillon\t".(hexdec($result[0].$result[1])/10)."\n", "3", "/root/humiditylog.log");

$modbus->deviceClose();
?>
