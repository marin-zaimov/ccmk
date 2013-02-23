<?php

/**
 * This is the model class for table "Group".
 *
 * The followings are the available columns in table 'Group':
 * @property integer $id
 * @property integer $creator
 * @property string $startDate
 * @property string $endDate
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Receipt[] $receipts
 * @property User[] $users
 */
class Group extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('creator, startDate, name', 'required'),
			array('id, creator', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('endDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, creator, startDate, endDate, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'receipts' => array(self::HAS_MANY, 'Receipt', 'groupId'),
			'users' => array(self::MANY_MANY, 'User', 'User_Group(groupId, userId)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'creator' => 'Creator',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'name' => 'Name',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('creator',$this->creator);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeValidate()
	{
	  if ($this->isNewRecord) {
	    $this->startDate = date('Y-m-d', time());
	  }
	  return parent::beforeValidate();
	}
}
