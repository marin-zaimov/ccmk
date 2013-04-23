<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $startDate
 * @property string $endDate
 * @property string $lastLogin
 * @property string $password_hash
 * @property string $password_salt
 * @property string $paypal_account
 *
 * The followings are the available model relations:
 * @property Receipt[] $receipts
 * @property Group[] $groups
 */
class User extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, startDate', 'required'),
			array('firstName, lastName, email, paypal_account', 'length', 'max'=>45),
      array('password_hash', 'length', 'max'=>255),
      array('password_salt', 'length', 'max'=>100),
			array('endDate, lastLogin', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, firstName, lastName, email, startDate, endDate, lastLogin, password_hash, password_salt, paypal_account', 'safe', 'on'=>'search'),
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
			'receipts' => array(self::HAS_MANY, 'Receipt', 'userId'),
			'groups' => array(self::MANY_MANY, 'Group', 'User_Group(userId, groupId)'),
			'paymentsOwed' => array(self::HAS_MANY, 'Payment', 'senderId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'email' => 'Email',
			'startDate' => 'Start Date',
			'endDate' => 'End Date',
			'lastLogin' => 'Last Login',
      'password_hash' => 'Password Hash',
      'password_salt' => 'Password Salt',
      'paypal_account' => 'Paypal Account',
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
		$criteria->compare('firstName',$this->firstName,true);
		$criteria->compare('lastName',$this->lastName,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('startDate',$this->startDate,true);
		$criteria->compare('endDate',$this->endDate,true);
		$criteria->compare('lastLogin',$this->lastLogin,true);
    $criteria->compare('password_hash',$this->password_hash,true);
    $criteria->compare('password_salt',$this->password_salt,true);
    $criteria->compare('paypal_account',$this->paypal_account,true);

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
