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
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class DimensionBehavior extends ModelBehavior {

/**
 * Variable that holds the options.
 * 
 * @var array
 * @access private
 */
	private $options = array();

/**
 * Variable that holds the default values.
 * 
 * @var array
 * @access private
 */
	private $defaults = array(
		'unique' => array()
	);

/**
 * Startup method to set the options.
 * 
 * @param 	object 	$model 		Model instance given by CakePHP.
 * @param 	array 	$options 	Options from the $actsAs definition.
 * @return 	void
 * @access 	public
 */
	public function setup($model, $options = array()) {
		$this->options[$model->alias] = array_merge($this->defaults, $options);
	}

/**
 * Method to get the identifying fields for the model.
 * 
 * @param 	object 	$model 		Model instance given by CakePHP.
 * @return 	array 				List of identifying fields.
 * @access 	public
 */
	public function getUniqueFields($model) {
		if (!$this->options[$model->alias]['unique']) {
			$fields = $model->schema();
			unset($fields[$model->primaryKey]);
			$this->options[$model->alias]['unique'] = array_keys($fields);
		}
		return $this->options[$model->alias]['unique'];		
	}
}
?>