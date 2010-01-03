<?php
class Ip extends Model {
	public $actsAs = array('Olap.Dimension');
	public $hasMany = array(
		'Request'
	);
}

class Moment extends Model {
	public $actsAs = array('Olap.Dimension' => array('unique' => array('day', 'month', 'year')));
	public $hasMany = array(
		'Request'
	);
}

class Request extends Model {
	public $actsAs = array('Olap.Facts');
	public $belongsTo = array(
		'Ip',
		'Location',
		'Moment'
	);
}

class DimensionBehaviorTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.olap.moment',
		'plugin.olap.ip',
		'plugin.olap.request',
		'plugin.olap.location'
	);
	
	private $Moment = null;
	private $Ip = null;
	
	public function startTest() {
		$this->Moment = ClassRegistry::init('Moment');
		$this->Ip = ClassRegistry::init('Ip');
	}

	public function testGetUniqueFields() {
		$expected = array('day', 'month', 'year');
		$result = $this->Moment->getUniqueFields(); 
		$this->assertIdentical($result, $expected);
		
		$expected = array('address');
		$result = $this->Ip->getUniqueFields(); 
		$this->assertIdentical($result, $expected);
	}
	
	public function endTest() {
		ClassRegistry::flush();
		unset($this->Moment);
		unset($this->Ip);
	}
}
?>