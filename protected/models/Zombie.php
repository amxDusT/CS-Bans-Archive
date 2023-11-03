<?php

class Zombie extends CActiveRecord
{
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'zp_ammo';
	}

    public function rules()
	{
		return array(
			array('player_nick, ammo', 'required'),
			array('player_nick', 'length', 'max'=>32),
			array('message', 'length', 'max'=>128),
			array('player_steamid', 'match', 'pattern' => '/^(STEAM_ID_ADDED)|(STEAM|VALVE)_([0-9]):([0-9]):\d{1,21}$/' ),
			array('player_ip', 'match', 'pattern' => '/^(IP_ADDED)|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|(IP_LAN)$/' ),
			array('ammo, total_ammo', 'numerical', 'integerOnly'=>true),
			array('id, player_nick, player_steamid, player_ip, ammo, message', 'safe', 'on'=>'search'),
		);
	}



	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'player_nick'		=> 'Nick',
			'ammo'				=> 'Ammo',
			'player_steamid'	=> 'SteamID',
			'player_ip'			=> 'IP',
			'total_ammo'		=> 'Total Ammo',
			'message'			=> 'Message'
		);
	}

    public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Removed zombie player <strong>' . $this->player_nick . '</strong>');
		return parent::afterDelete();
	}

	public function beforeSave() {
		if( $this->isNewRecord )
		{
			if(empty($this->player_steamid))
			$this->player_steamid = "STEAM_ID_ADDED";
			if(empty($this->player_ip))
				$this->player_ip = "IP_ADDED";
			$this->total_ammo = $this->ammo;
		}
		return parent::beforeSave();
	}
	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add(Logs::LOG_ADDED, 'Added new player zombie <strong>' . $this->player_nick . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Player zombie details changed <strong>' . $this->player_nick . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if($this->player_nick && Zombie::model()->count('`player_nick` = :id', array(
					':id' => $this->player_nick
				)))
			{
				return $this->addError($this->player_nick, 'This Player is already in the database.');
			}
		}

		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.player_nick',$this->player_nick);

		$criteria->order = '`ammo` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
}
