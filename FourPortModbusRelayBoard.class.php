<?php

class FourPortModbusRelayBoard {
	private $deviceID = false;
	private $localModbusDevice = false;
	private $modbus = false;
	private $relayAddresses = array(1 => "0000", 2 => "0001", 3 => "0002", 4 => "0003");
	private $autodisconnectAfterCommand = false;

	function __construct($localModbusDevice, $deviceID, $autodisconnectAfterCommand = false) {
		$this->deviceID = $deviceID;
		$this->localModbusDevice = $localModbusDevice;
		$this->modbus = new PhpSerialModbus;
		$this->modbus->deviceInit($this->localModbusDevice,9600,'none',8,1,'none');
		$this->modbus->deviceOpen();
		$this->autodisconnectAfterCommand = $autodisconnectAfterCommand;
	}

	 public function changeAddress($newDeviceID) {
                $newDeviceID = str_pad($newDeviceID, 4, '0', STR_PAD_LEFT);
                echo "setting new device id : ".$newDeviceID;
                $this->modbus->sendQuery($this->deviceID,6,"4000",$newDeviceID);
                if ($this->autodisconnectAfterCommand) {
                        $this->end();
                }
        }


	public function relayOn($relayNumber) {
		// 1 to 4. not 0 to 3
		$this->modbus->sendQuery($this->deviceID,5,$this->relayAddresses[$relayNumber],"0100");
		if ($this->autodisconnectAfterCommand) {
			$this->end();
		}
	}

	public function relayOff($relayNumber) {
		// 1 to 4. not 0 to 3
		$this->modbus->sendQuery($this->deviceID,5,$this->relayAddresses[$relayNumber],"0000");
		if ($this->autodisconnectAfterCommand) {
			$this->end();
		}
	}

	public function allOff() {
		$this->modbus->sendQuery($this->deviceID,5,"00ff","0000");
		if ($this->autodisconnectAfterCommand) {
			$this->end();
		}
	}

	public function allOn() {
		$this->modbus->sendQuery($this->deviceID,5,"00ff","ffff");
		if ($this->autodisconnectAfterCommand) {
			$this->end();
		}
	}

	public function end() {
		$this->modbus->deviceClose();
	}

}

?>
