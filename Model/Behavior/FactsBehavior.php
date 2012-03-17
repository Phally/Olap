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
 *     public $actsAs('Olap.Facts');
 * }
 *
 * Finding fact data will be done with Model::find().
 *
 * @author  Frank de Graaf (Phally)
 * @license MIT license
 * @link    http://github.com/Phally
 */
class FactsBehavior extends ModelBehavior {

	/**
	 * Property that holds the query cache for the dimensions.
	 *
	 * @var array
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
	 * 	    'TimeDimension' => array(
	 * 	        'day' => 5,
	 * 	        'month' => 12,
	 * 	        'year' => 2010
	 * 	    ),
	 * 	    'ClientDimension' => array(
	 * 	        'firstname' => 'Frank',
	 * 	        'lastname' => 'de Graaf'
	 * 	    ),
	 * 	    'ArticleDimension' => array(
	 * 	        'articlenumber' => '6K98-4'
	 * 	    )
	 * 	    number_of_articles => 4,
	 * 	    total_price => 26.00
	 * ));
	 *
	 * Foreign key information will be read from the belongsTo
	 * association array.
	 *
	 * @param   Model   $Model  Model instance, supplied by CakePHP.
	 * @param   array   $data   Fact data.
	 * @return  boolean         Success.
	 * @access  public
	 */
	public function saveFact(Model $Model, array $data) {
		foreach ($data as $alias => $values) {
			if (isset($Model->belongsTo[$alias])) {
				$serializedConditions = serialize($conditions = array_intersect_key($values, array_flip($Model->{$alias}->getUniqueFields())));

				$cached = isset($this->dimensionIds[$Model->alias][$alias][$serializedConditions]);
				if (!$cached) {
					$foreignId = $Model->{$alias}->field(
						$Model->{$alias}->primaryKey,
						$conditions
					);

					if (!$foreignId) {
						$Model->{$alias}->create($values);
						$Model->{$alias}->save();
						$this->dimensionIds[$Model->alias][$alias][$serializedConditions] = $Model->{$alias}->id;
					} else {
						$this->dimensionIds[$Model->alias][$alias][$serializedConditions] = $foreignId;
					}
				}
				$data[$Model->belongsTo[$alias]['foreignKey']] = $this->dimensionIds[$Model->alias][$alias][$serializedConditions];

				unset($data[$alias]);
			}
		}

		$Model->data = $data;
		$success = $Model->getDataSource()->create($Model);
		$Model->id = null;
		return $success;
	}
}
?>