<?php
/**
 * DimensionBehavior
 *
 * Part of the OLAP plugin and used by the FactsBehavior.
 *
 *
 * Usage with only identifying fields:
 *
 * class TimeDimension extends AppModel {
 * 		public $actsAs('Olap.Dimension');
 * }
 *
 *
 * Usage with additional data fields (you will need to
 * define the identifying fields):
 *
 * class TimeDimension extends AppModel {
 * 		public $actsAs = array('Olap.Dimension' => array('unique' => array('day', 'month', 'year')));
 * }
 *
 * @author  Frank de Graaf (Phally)
 * @license MIT license
 * @link    http://github.com/Phally
 */
class DimensionBehavior extends ModelBehavior {

	/**
	 * Property that holds the options.
	 *
	 * @var array
	 */
	protected $_options = array();

	/**
	 * Property that holds the default values.
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'unique' => array()
	);

	/**
	 * Startup method to set the options.
	 *
	 * @param Model $Model   Model instance given by CakePHP.
	 * @param array $options Options from the $actsAs definition.
	 */
	public function setup(Model $Model, array $options = array()) {
		$this->_options[$Model->alias] = $options + $this->_defaults;
	}

	/**
	 * Method to get the identifying fields for the model.
	 *
	 * @param  Model $Model Model instance given by CakePHP.
	 * @return array        List of identifying fields.
	 */
	public function getUniqueFields(Model $Model) {
		if (!$this->_options[$Model->alias]['unique']) {
			$fields = $Model->schema();
			unset($fields[$Model->primaryKey]);
			$this->_options[$Model->alias]['unique'] = array_keys($fields);
		}
		return $this->_options[$Model->alias]['unique'];
	}
}
?>