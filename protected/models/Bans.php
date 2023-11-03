<?php

class Bans extends CActiveRecord
{
	/**
	 * Флаг страны
	 * @var string
	 */
	public $country = null;
	//public $expiredTime = null;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{bans}}';
	}

	public function rules()
	{
		return array(
			array('player_nick', 'required'),
			array('ban_length', 'numerical', 'integerOnly'=>true),
			array('player_ip', 'match', 'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|(IP_LAN)$/'),
			array('player_id', 'match', 'pattern' => '/^(STEAM|VALVE)_([0-9]):([0-9]):\d{1,21}$/'),
			array('player_nick, ban_reason', 'length', 'max'=>100),
			array('ban_type', 'in', 'range' => array('S', 'SI','I')),
			array('update_ban', 'in', 'range' => array('0', '1','2')),
			array('expired', 'in', 'range' => array('0', '1')),
			//array('expiredTime', 'safe'),
			array('bid, player_ip, player_last_ip, player_id, player_nick, admin_ip, admin_id, admin_nick, ban_type, ban_reason, ban_created, ban_length, server_ip, server_name, ban_kicks, expired, ccode, update_ban', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'bid'				=> 'Bid',
			'player_ip'			=> 'Player IP',
			'player_last_ip'	=> 'Player Last IP',
			'player_id'			=> 'Player SteamID',
			'player_nick'		=> 'Player Nick',
			'admin_ip'			=> 'Admin IP',
			'admin_id'			=> 'Admin SteamID',
			'admin_nick'		=> 'Admin Nick',
			'ban_type'			=> 'Ban Type',
			'ban_reason'		=> 'Reason',
			'ban_created'		=> 'Date',
			'ban_length'		=> 'Ban Length',
			'server_ip'			=> 'Server IP',
			'server_name'		=> 'Server Name',
			'ban_kicks'			=> 'Kicks',
			'expired'			=> 'Expired',
			'ccode'				=> 'CBan Code',
			'update_ban'		=> 'Update Ban',
		);
	}

   
    
	public function getUnbanned() {
		return $this->ban_length == '-1' || $this->expired == 1 || ($this->ban_length && ($this->ban_created + ($this->ban_length * 60)) < time());
	}
	
	protected function afterFind() {
		$country = strtolower(Yii::app()->IpToCountry->lookup($this->player_ip));
		$dump = substr(Yii::app()->urlManager->baseUrl, 0, -10);
		$this->country = "dust";
		$this->country = CHtml::image(
            $dump 
            . '/images/country/' 
            . ($country != 'zz' && $country!=false? $country : 'clear') . '.png'
		);
		//throw new Exception($this->country);
        return parent::afterFind();
	}

	protected function beforeSave() {
		if($this->isNewRecord) {
			$this->ban_created = time();
		}
		return parent::beforeSave();
	}

	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add(Logs::LOG_ADDED, 'Added new player Ban <strong>' . $this->player_nick . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Player ban details Changed <strong>' . $this->player_nick . '</strong>');
        }
        return parent::afterSave();
	}

	public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Player ban Removed <strong>' . $this->player_nick . '</strong>');
		return parent::afterDelete();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if (!filter_var($this->player_ip, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4))) {
				if( $this->ban_type!='S' )
					return $this->addError($this->player_ip, 'Invalid IP');
				else
					$this->player_ip = "IP_LAN";
            }

            if($this->player_ip && Bans::model()->count('`player_ip` = :ip AND `ban_type` LIKE \'%I%\' AND `expired` = 0 AND (`ban_length` = 0 OR `ban_created` + (`ban_length` * 60) >= UNIX_TIMESTAMP())', array(
					':ip' => $this->player_ip
				)))
			{
				return $this->addError($this->player_ip, 'This IP is already banned');
			}
			
			if($this->player_id && Bans::model()->count('`player_id` = :id AND `ban_type` LIKE \'%S%\' AND `expired` = 0 AND (`ban_length` = 0 OR `ban_created` + (`ban_length` * 60) >= UNIX_TIMESTAMP())', array(
					':id' => $this->player_id
				)))
			{
				return $this->addError($this->player_id, 'This SteamID is already banned');
			}
		}

		return parent::beforeValidate();
	}

	/**
	 * Возвращает список банов для селекта
	 * @return array
	 */
	public static function getBanLenght()
	{
		return array(
			'0'			=> 'Permanent',
			'5'			=> '5 Minutes',
			'10'		=> '10 Minutes',
			'15'		=> '15 Minutes',
			'30'		=> '30 Minutes',
			'60'		=> '1 Hour',
			'120'		=> '2 Hours',
			'180'		=> '3 Hours',
			'300'		=> '5 Hours',
			'600'		=> '10 Hours',
			'1440'		=> '1 Day',
			'4320'		=> '3 Days',
			'10080'		=> '1 Week',
			'20160'		=> '2 Weeks',
			'43200'		=> '1 Month',
			'129600'	=> '3 Months',
			'259200'	=> '6 Months',
			'518400'	=> '1 Year',
		);
	}

	/**
	 * Возвращает дату истечения бана
	 * @return string
	 */
	public function getExpiredTime()
	{
		return Prefs::getExpired($this->ban_created, $this->ban_length);
	}
    
	/**
	 * Настройки поиска
	 * @return \CActiveDataProvider
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('bid',$this->bid);
		$criteria->addSearchCondition('player_ip',$this->player_ip);
		$criteria->addSearchCondition('player_id',$this->player_id);
		$criteria->addSearchCondition('player_nick',$this->player_nick);
		$criteria->compare('admin_ip',$this->admin_ip,true);
		$criteria->compare('admin_id',$this->admin_id,true);
		if ($this->admin_nick) {
            $criteria->compare('admin_nick', $this->admin_nick, true);
        }
        $criteria->compare('ban_type',$this->ban_type,true);
		$criteria->addSearchCondition('ban_reason',$this->ban_reason);
		if ($this->ban_created) {
            $start = strtotime("{$this->ban_created} 00:00:00");
            $end = strtotime("{$this->ban_created} 23:59:59");
            $criteria->addBetweenCondition('ban_created', $start, $end);
        }
        $criteria->compare('ban_length',$this->ban_length);
		$criteria->compare('server_ip',$this->server_ip,true);
		$criteria->compare('server_name',$this->server_name,true);
		$criteria->compare('ban_kicks',$this->ban_kicks);
		$criteria->compare('expired',$this->expired);

		$criteria->order = '`bid` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
}
