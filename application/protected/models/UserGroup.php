<?php

/**
 * This is the model class for table "User_Group".
 *
 * The followings are the available columns in table 'User_Group':
 * @property integer $userId
 * @property integer $groupId
 * @property string $startDate
 * @property string $endDate
 * @property integer $invitedBy
 */
class UserGroup extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserGroup the static model class
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
		return 'User_Group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, groupId, startDate', 'required'),
			array('userId, groupId, invitedBy', 'numerical', 'integerOnly'=>true),
			array('endDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('userId, groupId, startDate, endDate, invitedBy', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userId' => 'User',
			'groupId' => 'Group',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'invitedBy' => 'Invited By',
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

		$criteria->compare('userId',$this->userId);
		$criteria->compare('groupId',$this->groupId);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('invitedBy',$this->invitedBy);

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
