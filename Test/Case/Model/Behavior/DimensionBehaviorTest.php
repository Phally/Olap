<?php
class Ip extends CakeTestModel {
	public $actsAs = array('Olap.Dimension');
	public $hasMany = array('Request');
}

class Moment extends CakeTestModel {
	public $actsAs = array('Olap.Dimension' => array('unique' => array('day', 'month', 'year')));
	public $hasMany = array('Request');
}

class Request extends CakeTestModel {
	public $actsAs = array('Olap.Facts');
	public $belongsTo = array('Ip', 'Location', 'Moment');
}

class DimensionBehaviorTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.olap.moment',
		'plugin.olap.ip',
		'plugin.olap.request',
		'plugin.olap.location'
	);

	private $Moment = null;
	private $Ip = null;

	public function setUp() {
		parent::setUp();

		$this->Moment = ClassRegistry::init('Moment');
		$this->Ip = ClassRegistry::init('Ip');
	}

	public function testGetUniqueFields() {
		$expected = array('day', 'month', 'year');
		$result = $this->Moment->getUniqueFields();
		$this->assertSame($result, $expected);

		$expected = array('address');
		$result = $this->Ip->getUniqueFields();
		$this->assertSame($result, $expected);
	}

	public function tearDown() {
		parent::tearDown();

		ClassRegistry::flush();
		unset($this->Moment);
		unset($this->Ip);
	}
}
?>