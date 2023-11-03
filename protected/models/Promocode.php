<?php
/*
id INT NOT NULL AUTO_INCREMENT,\
codename VARCHAR(64),\
code VARCHAR(40) NOT NULL UNIQUE,\
type INT(2) NOT NULL,\
used INT(2) NOT NULL,\
created INT(11) NOT NULL,\
expires INT(11) NOT NULL,\
redeemedAt INT,\
redeemedBy VARCHAR(32),\
redeemedBySteam VARCHAR(32),\
*/
class Promocode extends CActiveRecord
{
	public $info = null;
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'promo_codes';
	}

    public function rules()
	{
		return array(
			array('type', 'required'),
			array('created, expires, redeemedAt', 'numerical','integerOnly'=>true),
			array('used', 'in', 'range'=>array('0','1')),
			array('codename', 'length', 'max'=>35),
			array('redeemedBy, redeemedBySteam', 'length', 'max'=>32 ),
			array('id, codename, code, type, used, created, expires, redeemedAt,redeemedBy,redeemedBySteam', 'safe', 'on'=>'search'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'codename'		    => 'Tag Name',
			'code'				=> 'Code',
			'type'				=> 'Code Type',
			'used'				=> 'Used',
			'created'			=> 'Date Created',
			'expires'			=> 'Date Expire',
			'redeemedAt'		=> 'Date Redeemed',
			'redeemedBy'		=> 'Redeemed By',
			'redeemedBySteam'	=> 'SteamID Redeemer'
		);
	}

    public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Removed code <strong>' . $this->codename . "|" . $this->redeemedBy . '</strong>');
		return parent::afterDelete();
	}

	protected function beforeSave() {
      
		$this->created = time();
		if( !is_numeric( $this->type ) )
			$this->type = self::GetRandomType();
		$this->used = 0;
		$this->expires = time() + 86400*30; // 30 days;
		$this->code = self::GenerateCode();
		return parent::beforeSave();
	}


	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add( "Added Code", 'Added new server <strong>' . $this->codename . "|" . $this->type . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Server details changed <strong>' . $this->codename . "|" . $this->redeemedBy . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.codename',$this->hostname);
		$criteria->addSearchCondition('t.type',$this->address);
		$criteria->addSearchCondition('t.created',$this->port);
		$criteria->addSearchCondition('t.used',$this->stop_cmd);

		$criteria->order = '`id` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
	protected static function GetRandomType()
	{
		return random_int( 0, 9 );
	}

	public static function GetPromos( $include_random = false, $specific = false, $specify = 0 )
	{
		$return = array(
			'0'=>'VIP 1 Day',
			'1'=>'VIP 1 Week',
			'2'=>'VIP 1 Month',
			'3'=>'VIP 2 Months',
			'4'=>'VIP 3 Months',
			'5'=>'SVIP 1 Month',
			'6'=>'SVIP 2 Months',
			'7'=>'SVIP 3 Months',
			'8'=>'ADMIN 1 Day',
			'9'=>'ADMIN 1 Week',
			'10'=>'ADMIN 1 Month',
			'11'=>'ADMIN 2 Months',
			'12'=>'ADMIN 3 Months',
			'13'=>'SADMIN 1 Month',
			'14'=>'SADMIN 2 Months',
			'15'=>'SADMIN 3 Months'
		);
		if( $include_random == true )
			$return['rnd'] = 'Random';
		
		if( $specific == true && isset( $return[$specify] ) )
			return $return[ $specify ];

		return $return;
	}


	protected static function GenerateCode( $len = 16 ) 
	{
        $arrlet = array_merge( range( 'A', 'Z' ),range( 'A', 'Z' ), range( '0', '9' ), range( '0', '9' ) );
        //$word = $word + $numbers;
        shuffle( $arrlet );
        return "CODEAMX-" . substr( implode( $arrlet ), 0, $len/2 ) . "-" . substr( implode( $arrlet ), $len/2, $len/2 ) ;    
	}

	/*
	'0'=>'VIP 1 Day',
	'1'=>'VIP 1 Week',
	'2'=>'VIP 1 Month',
	'3'=>'VIP 2 Months',
	'4'=>'VIP 3 Months',
	'5'=>'SVIP 1 Month',
	'6'=>'SVIP 2 Months',
	'7'=>'SVIP 3 Months',
	'8'=>'ADMIN 1 Day',
	'9'=>'ADMIN 1 Week',
	'10'=>'ADMIN 1 Month',
	'11'=>'ADMIN 2 Months',
	'12'=>'ADMIN 3 Months',
	'13'=>'SADMIN 1 Month',
	'14'=>'SADMIN 2 Months',
	'15'=>'SADMIN 3 Months'
	*/

	public static function PaypalGetType( $currency, $cost, $option )
	{
		if( $currency != 'EUR' )
			return -1;
		if( $option == 'VIP' )
		{
			if( $cost == '2.00' )
				return 2;
			else if( $cost == '4.00')
				return 3;
			else if( $cost == '5.00' )
				return 4;
			else
				return -1;	// error? 
		}
		else if( $option == 'SuperVIP' )
		{
			if( $cost == '3.00' )
				return 5;
			else if( $cost == '6.00' )
				return 6;
			else if( $cost == '8.00' )
				return 7;
			else
				return -1;
		}
		else if( $option == 'Admin' )
		{
			if( $cost == '4.00' )
				return 10;
			else if( $cost == '7.00' )
				return 11;
			else if( $cost == '10.00' )
				return 12;
			else return -1;
		}
	}
}
