<?php
/**
 * @property integer $id ID бана
 * @property string $steamid Стим игрока
 * @property string $name Ник игрока
 * @property string $ip IP игрока
 * @property string $admin_name Ник админа
 * @property string $admin_steamid Стим админа
 * @property integer $create_time Дата бана
 * @property integer $expired_time Дата истечения бана
 * @property integer $reason Причина
 *
 * The followings are the available model relations:
 * @property Amxadmins $admin
 */
class Gags extends CActiveRecord
{
	public $country = null;
	public $length = null;
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'ucc_gag';
	}

	public function rules()
	{
		return array(
			array('name, admin_name', 'required'),
			array('ip', 'match', 'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'),
			array('steamid', 'match', 'pattern' => '/^(STEAM|VALVE)_([0-9]):([0-9]):\d{1,21}$/'),
			array('reason', 'length', 'max'=>64),
			array('length', 'numerical', 'integerOnly'=>true),
			array('block_type', 'in', 'range' => array('0','1','2')),
			array('id, steamid, name, ip, admin_name, admin_steamid, create_time, expired_time', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'					=> 'ID',
			'ip'		    		=> 'IP',
			'steamid'				=> 'SteamID',
			'create_time'			=> 'Date',
			'name'		        	=> 'Nick',
			'expired_time'			=> 'Expires',
			'admin_name'			=> 'Admin',
			'reason'            	=> 'Reason',
			'block_type'            => 'Gag Type'
		);
	}

	protected function afterFind() {
		$length = $this->expired_time - $this->create_time;
		$country = strtolower(Yii::app()->IpToCountry->lookup($this->ip));
		$dump = substr(Yii::app()->urlManager->baseUrl, 0, -10);
		$this->country = CHtml::image(
            $dump 
            . '/images/country/' 
            . ($country != 'zz' && $country!=false? $country : 'clear') . '.png'
		);
        return parent::afterFind();
	}

    public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Removed gagged player <strong>' . $this->name . '</strong>');
		return parent::afterDelete();
	}

	protected function beforeSave() {
		if($this->isNewRecord) {
			$this->create_time = time();
			if( $this->length > 0 )
				$this->expired_time = time() + $this->length;
			else
				$this->expired_time = $this->length;
		} else {
			$oldgag = self::model()->findByPk($this->id);
			if( $this->length > 0 )
				$this->expired_time = $oldgag->create_time + $this->length;
			else
				$this->expired_time = $this->length;
		}
		return parent::beforeSave();
	}


	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add( "Added Gag", 'Added new player gag <strong>' . $this->name . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Player gag details changed <strong>' . $this->name . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if (!filter_var($this->ip, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4))) {
                return $this->addError($this->ip, 'Invalid IP');
            }

            if($this->ip && Gags::model()->count('`ip` = :ip AND (`expired_time` <> -1 OR `expired_time` > UNIX_TIMESTAMP())', array(
					':ip' => $this->ip
				)))
			{
				return $this->addError($this->ip, 'This IP is already gagged');
			}
			
			if($this->steamid && Gags::model()->count('`steamid` = :id AND (`expired_time` <> -1 OR `expired_time` > UNIX_TIMESTAMP())', array(
					':id' => $this->steamid
				)))
			{
				return $this->addError($this->steamid, 'This SteamID is already gagged');
			}

			$this->admin_name = Webadmins::GetName();
			$this->admin_ip = $_SERVER['REMOTE_ADDR'];
			$this->admin_steamid = 'STEAMID_LAN';
		}

		return parent::beforeValidate();
	}

	public function GetGagFlags()
	{
		switch( $this->block_type )
		{
			case 0: {$flags = "Chat"; break;}
			case 1: {$flags = "Voice"; break;}
			case 2: {$flags = "Chat + Voice"; break;}
		}

		return $flags;	
	}
    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.ip',$this->ip);
		$criteria->addSearchCondition('t.steamid',$this->steamid);
		$criteria->addSearchCondition('t.name',$this->name);
        $criteria->addSearchCondition('t.admin_name',$this->admin_name);

		$criteria->order = '`create_time` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
	public static function getGagLength()
	{
		return array(
			'-1'		=> 'Ungag',
			'0'			=> 'Permanent',
			'300'		=> '5 Minutes',
			'600'		=> '10 Minutes',
			'900'		=> '15 Minutes',
			'1800'		=> '30 Minutes',
			'3600'		=> '1 Hour',
			'7200'		=> '2 Hours',
			'10800'		=> '3 Hours',
			'18000'		=> '5 Hours',
			'36000'		=> '10 Hours',
			'86400'		=> '1 Day',
			'259200'	=> '3 Days',
			'604800'	=> '1 Week',
		);
	}
}
