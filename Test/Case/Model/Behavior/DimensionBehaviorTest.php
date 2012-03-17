<?php
class Ip extends CakeTestModel {
	public $actsAs = array('Olap.Dimension');
}

class Moment extends CakeTestModel {
	public $actsAs = array(
		'Olap.Dimension' => array(
			'unique' => array('day', 'month', 'year')
		)
	);
}

class DimensionBehaviorTest extends CakeTestCase {
	public $fixtures = array(
		'plugin.olap.moment',
		'plugin.olap.ip',
	);

	protected $_Moment = null;
	protected $_Ip = null;

	public function setUp() {
		parent::setUp();

		$this->_Moment = ClassRegistry::init('Moment');
		$this->_Ip = ClassRegistry::init('Ip');
	}

	public function testGetUniqueFields() {
		$expected = array('day', 'month', 'year');
		$result = $this->_Moment->getUniqueFields();
		$this->assertSame($result, $expected);

		$expected = array('address');
		$result = $this->_Ip->getUniqueFields();
		$this->assertSame($result, $expected);
	}

	public function tearDown() {
		parent::tearDown();

		ClassRegistry::flush();
		unset($this->_Moment);
		unset($this->_Ip);
	}
}
?>