<?php
/**
 * FactsBehavior
 *
 * Part of the OLAP plugin.
 *
 *
 * Usage:
 *
 * class SaleFact extends AppModel {
 * 		public $actsAs('Olap.Facts');
 * }
 *
 * Finding fact data will be done with Model::find().
 *
 * @author Frank de Graaf (Phally)
 * @license MIT license
 * @link http://github.com/phally
 */
class FactsBehavior extends ModelBehavior {

/**
 * Variable that holds the query cache for the dimensions.
 *
 * @var array
 * @access public
 */
	public $dimensionIds = array();

/**
 * Method to save facts.
 *
 * Dimension records will be automatically associated/created. All
 * dimensions have meaningless keys to be compatible with CakePHP,
 * the keys are not included in the call though:
 *
 * $this->SalesFact->saveFact(array(
 * 		'TimeDimension' => array(
 * 			'day' => 5,
 * 			'month' => 12,
 * 			'year' => 2010
 * 		),
 * 		'ClientDimension' => array(
 * 			'firstname' => 'Frank',
 * 			'lastname' => 'de Graaf'
 * 		),
 * 		'ArticleDimension' => array(
 * 			'articlenumber' => '6K98-4'
 * 		)
 * 		number_of_articles => 4,
 * 		total_price => 26.00
 * ));
 *
 * Foreign key information will be read from the belongsTo
 * association array.
 *
 * @param 	object 	$model 	Model instance, supplied by CakePHP.
 * @param 	array 	$data 	Fact data.
 * @return 	boolean 		Success.
 * @access 	public
 */
	public function saveFact($model, $data) {
		foreach ($data as $alias => $values) {
			if (isset($model->belongsTo[$alias])) {
				$serializedConditions = serialize($conditions = array_intersect_key($values, array_flip($model->{$alias}->getUniqueFields())));

				$cached = isset($this->dimensionIds[$model->alias][$alias][$serializedConditions]);
				if (!$cached) {
					$foreignId = $model->{$alias}->field(
						$model->{$alias}->primaryKey,
						$conditions
					);

					if (!$foreignId) {
						$model->{$alias}->create($values);
						$model->{$alias}->save();
						$this->dimensionIds[$model->alias][$alias][$serializedConditions] = $model->{$alias}->id;
					} else {
						$this->dimensionIds[$model->alias][$alias][$serializedConditions] = $foreignId;
					}
				}
				$data[$model->belongsTo[$alias]['foreignKey']] = $this->dimensionIds[$model->alias][$alias][$serializedConditions];

				unset($data[$alias]);
			}
		}

		$model->data = $data;
		$success = $model->getDataSource()->create($model);
		$model->id = null;
		return $success;
	}
}
?>