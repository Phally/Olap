<?php
class Ip extends Model {
	public $actsAs = array('Olap.Dimension');
	public $hasMany = array(
		'Request'
	);
}

class Location extends Model {
	public $actsAs = array('Olap.Dimension');
	public $hasMany = array(
		'Request'
	);
}

class Moment extends Model {
	public $actsAs = array('Olap.Dimension' => array('unique' => array('hour', 'day', 'month', 'year')));
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

class FactsBehaviorTestCase extends CakeTestCase {
	public $fixtures = array(
		'plugin.olap.moment',
		'plugin.olap.ip',
		'plugin.olap.location',
		'plugin.olap.request',
	);
	
	private $Request = null;
	
	public function startTest() {
		$this->Request = ClassRegistry::init('Request');
	}
	
	public function testSaveFactWithOneDimensionRecordCreated() {
		$this->Request->saveFact(array(
			'Location' => array(
				'path' => 'app/articles/show'
			),
			'Ip' => array(
				'address' => '192.168.1.100'
			),
			'Moment' => array(
				'hour' => 19,
				'day' => 22,
				'month' => 9,
				'year' => 2010
			),
			'number_of_visits' => 18
		));
		
		$result = $this->Request->find('first', array(
			'conditions' => array(
				'Location.path' => 'app/articles/show',
				'Ip.address' => '192.168.1.100',
				'Moment.hour' => 19, 
				'Moment.day' => 22, 
				'Moment.month' => 9, 
				'Moment.year' => 2010, 
			)
		));

		$expected = array(
			'Request' => array(
				'ip_id' => '1',
				'location_id' => '1', 
				'moment_id' => '5',
				'number_of_visits' => '18'
			),
			'Ip' => array(
				'id' => '1',
				'address' => '192.168.1.100'
			),
			'Location' => array(
				'id' => '1',
				'path' => 'app/articles/show'
			),
			'Moment' => array(
				'id' => '5',
				'hour' => '19',
				'day' => '22',
				'month' => '9',
				'year' => '2010'
			)
		);
		
		$this->assertIdentical($result, $expected, 'New record made for Moment and the rest associated. Fact has been saved. %s');
	}
	
	public function testSaveFactWithAllDimensionRecordsCreated() {
		$this->Request->saveFact(array(
			'Location' => array(
				'path' => 'app/pictures/edit'
			),
			'Ip' => array(
				'address' => '123.123.123.123'
			),
			'Moment' => array(
				'hour' => 21,
				'day' => 14,
				'month' => 2,
				'year' => 2009
			),
			'number_of_visits' => 1232
		));
		
		$result = $this->Request->find('first', array(
			'conditions' => array(
				'Location.path' => 'app/pictures/edit',
				'Ip.address' => '123.123.123.123',
				'Moment.hour' => 21, 
				'Moment.day' => 14, 
				'Moment.month' => 2, 
				'Moment.year' => 2009, 
			)
		));

		$expected = array(
			'Request' => array(
				'ip_id' => '5',
				'location_id' => '5', 
				'moment_id' => '5',
				'number_of_visits' => '1232'
			),
			'Ip' => array(
				'id' => '5',
				'address' => '123.123.123.123'
			),
			'Location' => array(
				'id' => '5',
				'path' => 'app/pictures/edit'
			),
			'Moment' => array(
				'id' => '5',
				'hour' => '21',
				'day' => '14',
				'month' => '2',
				'year' => '2009'
			)
		);
		
		$this->assertIdentical($result, $expected, 'New records are made in the dimensions. Fact has been saved. %s');
	}

	public function testSaveFactWithNoDimensionRecordsCreated() {
		$this->Request->saveFact(array(
			'Location' => array(
				'path' => 'content_management/pages/display'
			),
			'Ip' => array(
				'address' => '127.0.0.1'
			),
			'Moment' => array(
				'hour' => 8, 
				'day' => 14, 
				'month' => 9, 
				'year' => 2010
			),
			'number_of_visits' => 12345
		));
		
		$result = $this->Request->find('first', array(
			'conditions' => array(
				'Location.path' => 'content_management/pages/display',
				'Ip.address' => '127.0.0.1',
				'Moment.hour' => 8, 
				'Moment.day' => 14, 
				'Moment.month' => 9, 
				'Moment.year' => 2010, 
				'Request.number_of_visits' => 12345
			)
		));

		$expected = array(
			'Request' => array(
				'ip_id' => '4',
				'location_id' => '3', 
				'moment_id' => '3',
				'number_of_visits' => '12345'
			),
			'Ip' => array(
				'id' => '4',
				'address' => '127.0.0.1'
			),
			'Location' => array(
				'id' => '3',
				'path' => 'content_management/pages/display'
			),
			'Moment' => array(
				'id' => '3',
				'hour' => '8',
				'day' => '14',
				'month' => '9',
				'year' => '2010'
			)
		);
		
		$this->assertIdentical($result, $expected, 'All dimension records are found and associated. Fact has been saved. %s');
	}

	public function testFind() {
		
		$expected = array(
			0 => array(
				'Ip' => array(
					'id' => '3',
					'address' => '24.214.123.200'
				),
				'Request' => array(
					'count' => '511'
				)
			)
		);
		
		$this->Request->virtualFields = array('count' => 'SUM(Request.number_of_visits)');
		$result = $this->Request->find('all', array(
			'fields' => array('Ip.id', 'Ip.address', 'count'),
			'conditions' => array(
				'Moment.day' => 14,
				'Moment.month' => 4,
				'Moment.year' => 2010
			),
			'group' => array('Ip.id', 'Ip.address'),
		));
		
		$this->assertIdentical($result, $expected);
	}
	
	public function endTest() {
		ClassRegistry::flush();
		unset($this->Request);
	}
}
?>