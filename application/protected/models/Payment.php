<?php

/**
 * This is the model class for table "Payment".
 *
 * The followings are the available columns in table 'Payment':
 * @property integer $id
 * @property integer $senderId
 * @property integer $receiverId
 * @property string $startDate
 * @property string $endDate
 * @property double $amountDue
 * @property integer $receiptId
 * @property double $amountPaid
 *
 * The followings are the available model relations:
 * @property Receipt $receipt
 */
class Payment extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Payment the static model class
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
		return 'Payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('senderId, receiverId, startDate, amountDue, receiptId', 'required'),
			array('id, senderId, receiverId, receiptId', 'numerical', 'integerOnly'=>true),
			array('amountDue, amountPaid', 'numerical'),
			array('endDate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, senderId, receiverId, startDate, endDate, amountDue, receiptId, amountPaid', 'safe', 'on'=>'search'),
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
			'receipt' => array(self::BELONGS_TO, 'Receipt', 'receiptId'),
			'receiver' => array(self::BELONGS_TO, 'User', 'receiverId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'senderId' => 'Sender',
			'receiverId' => 'Receiver',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'amountDue' => 'Amount Due',
			'receiptId' => 'Receipt',
			'amountPaid' => 'Amount Paid',
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
		$criteria->compare('senderId',$this->senderId);
		$criteria->compare('receiverId',$this->receiverId);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('amountDue',$this->amountDue);
		$criteria->compare('receiptId',$this->receiptId);
		$criteria->compare('amountPaid',$this->amountPaid);

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
