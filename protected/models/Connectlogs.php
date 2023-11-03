<?php
class Connectlogs extends CActiveRecord
{
	public $tdate = null;
	public $isEmpty = null;
	public $pl = null;
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'connect_logs';
	}

	public function rules()
	{
		return array(
			array('nick, steamid, ip', 'required'),
			array('ip', 'match', 'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'),
			array('steamid', 'match', 'pattern' => '/^(STEAM|VALVE)_([0-9]):([0-9]):\d{1,21}$/'),
			array('played_time', 'numerical', 'integerOnly'=>true),
			array('id, steamid, nick, ip', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'					=> 'ID',
			'ip'		    		=> 'IP',
			'steamid'				=> 'SteamID',
			'nick'		        	=> 'Nick',
            'date'                  => 'Date',
            'played_time'           => 'Played Time'
		);
	}
    public function search()
	{
		$criteria=new CDbCriteria();
		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.ip',$this->ip);
		$criteria->addSearchCondition('t.steamid',$this->steamid);
		$criteria->addSearchCondition('t.nick',$this->nick);

		$criteria->order = '`date` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
}
