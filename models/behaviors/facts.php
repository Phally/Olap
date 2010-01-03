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
				
				$foreignId = $model->{$alias}->field(
					$model->{$alias}->primaryKey, 
					array_intersect_key($values, array_flip($model->{$alias}->getUniqueFields()))
				);
				
				if (!$foreignId) {
					$model->{$alias}->create($values);
					$model->{$alias}->save();
					$data[$model->belongsTo[$alias]['foreignKey']] = $model->{$alias}->id;
				} else {
					$data[$model->belongsTo[$alias]['foreignKey']] = $foreignId;
				}
				
				unset($data[$alias]);
			}
		}
		
		$model->data = $data;
		if ($model->getDataSource()->create($model)) {
			$model->id = null;
			return true;
		}
		
		return false;
	}
}
?>