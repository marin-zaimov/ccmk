<?php

/**
 * This is the model class for table "Receipt".
 *
 * The followings are the available columns in table 'Receipt':
 * @property integer $id
 * @property double $amountDue
 * @property integer $userId
 * @property integer $groupId
 * @property string $picture
 * @property string $name
 * @property string $status
 *
 * The followings are the available model relations:
 * @property Payment[] $payments
 * @property Group $group
 * @property User $user
 */
class Receipt extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Receipt the static model class
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
		return 'Receipt';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId, groupId', 'required'),
			array('id, userId, groupId', 'numerical', 'integerOnly'=>true),
			array('amountDue', 'numerical'),
			array('picture', 'length', 'max'=>255),
			array('name', 'length', 'max'=>45),
			array('status', 'length', 'max'=>6),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, amountDue, userId, groupId, picture, name, status', 'safe', 'on'=>'search'),
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
			'payments' => array(self::HAS_MANY, 'Payment', 'receiptId'),
			'group' => array(self::BELONGS_TO, 'Group', 'groupId'),
			'user' => array(self::BELONGS_TO, 'User', 'userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'amountDue' => 'Amount Due',
			'userId' => 'User',
			'groupId' => 'Group',
			'picture' => 'Picture',
			'name' => 'Name',
			'status' => 'Status',
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
		$criteria->compare('amountDue',$this->amountDue);
		$criteria->compare('userId',$this->userId);
		$criteria->compare('groupId',$this->groupId);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function isPaid()
	{
		foreach ($this->payments as $p) {
			if ($p->amountDue != $p->amountPaid) {
				return 'false';
			}
		}
		return 'true';
	}
}
