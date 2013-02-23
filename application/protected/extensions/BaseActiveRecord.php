<?php

class BaseActiveRecord extends CActiveRecord
{
	public function getLastId()
	{
		return static::model()->lastInserted()->find()->id;
	}

	public function setFromArray($modelDataArray)
	{
		$this->setAttributes($modelDataArray, false);
	}
	
	public function attributes($relationNames = array(), $asObject = true)
	{
		$attributes = parent::getAttributes();
		
		$modelRelations = array();
		
		//get model's relation names
		foreach ($this->relations() as $relationName => $relation) {
			$modelRelations[] = $relationName;
		}
		//add partial relations, if specified
		if (!empty($relationNames) && is_array($relationNames)) {

			foreach ($relationNames as $relationName) {
				if (in_array($relationName, $modelRelations)) {
					$relationAttributes = array();
					$relationModels = $this->$relationName;
					if (is_array($relationModels)) {
						foreach ($relationModels as $model) {
							if ($asObject) {
								$relationAttributes[] = (object) $model->attributes;
							}
							else {
								$relationAttributes[] = $model->attributes;
							}
						}
					}
					else {
					  if ($asObject) {
					    $relationAttributes = (object) $relationModels->attributes;
					  }
					  else {
						  $relationAttributes = $relationModels->attributes;
						}
					}
					if ($asObject) {
						$relationAttributes = $relationAttributes;
					}
					$attributes[$relationName] = $relationAttributes;
				}
				else {
					throw new Exception("The ".get_called_class()." class doesn't have relation [".$relationName ."]");
				}
			}
		}
		//add all relations
		else if ($relationNames === true) {
		
			foreach ($modelRelations as $relationName) {
	
				$relationAttributes = array();
				if (is_array($this->$relationName)) {
					foreach ($this->$relationName as $model) {
						if ($asObject) {
							$relationAttributes[] = (object) $model->attributes;
						}
						else {
							$relationAttributes[] = $model->attributes;
						}
					}
				}
				else {
					$relationAttributes = $this->$relationName->attributes;
				}
				
				if ($asObject) {
					$relationAttributes = (object) $relationAttributes;
				}
				$attributes[$relationName] = $relationAttributes;
			}
			
		}
		
		if ($asObject === true) {
			$attributes = (object) $attributes;
		}
		return $attributes;
	}

    
	public static function idExists($id)
	{
		$objExists = null;
		try {
			static::getById($id);
			$objExists = true;
		}
		catch (Exception $ex) {
			$objExists = false;
		}
		
		return $objExists;
	}

	// REPOSITORY METHODS ///////
	public static function getAll($params = null)
	{
		$allObjs = static::model()->findAll($params);
		return $allObjs;
	}

	/**
	 *	@return instance of calling class or possibly null when no row found.
	 */
	public static function getById($id, $throwExceptionNoRowFound = true)
	{
		$obj = static::model()->findByPk($id);

		if (!isset($obj) && $throwExceptionNoRowFound) {
			throw new InvalidIdException('No ' . get_called_class() . ' found with id: ' . $id);
		}

		return $obj;
	}

	public static function getByIdList(array $idList)
	{
		$objList = static::model()->byIdList($idList)->findAll();
		return $objList;
	}

	public static function store($obj)
	{
		if (!$obj->save()) {
			Yii::log("not saved","trace");
			if ($obj->hasErrors()) {
				throw new ValidationException(get_called_class() . ' failed validation.', $obj->errors);
			}
			else {
				throw new Exception('Save failed for an unknown reason. ');
			}
		}
		else {
			Yii::log(get_called_class() . ' saved successfully', 'trace');
		}
	}

	public static function remove($id)
	{
		$obj = static::model()->findByPk($id);
		if(isset($obj)) {
			if(!$obj->delete()) {
				$message = "Deletion of " . get_called_class(). " with ID: {$id} failed";
				throw new Exception($message);
			}
		}
		else {
			  throw new InvalidIdException("No " . get_called_class() . " was found with ID: {$id}");
		}
	}
	
	// FACTORY METHODS
	public static function createFromForm($formData)
	{
		return self::createFromArray($formData);
	}
	
	public static function createFromArray($data)
	{

		$obj = new static();
		$obj->setAttributes($data, false);

		return $obj;
	}

	// SCOPING METHODS ///////
	public function byIdList($list)
	{
		$obj = new static();
		$obj->getDbCriteria()->addInCondition('id', $list);
		return $obj;
	}

	// SCOPING METHODS ///////
    public function scopes()
    {
          return array(
                'lastInserted' => array(
                      'order' => 'id desc',
					  'limit' => 1
                )
          );
          
    }
	
}
