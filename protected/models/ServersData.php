<?php

class ServersData extends CActiveRecord
{
	public $playersinfo = null;
	//public $connect = null;
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'servers_data';
	}

    public function rules()
	{
		return array(
			array('server_id, map, nextmap, players, maxplayers, timeleft, hostname', 'required'),
			array('server_id, players, maxplayers', 'numerical', 'integerOnly'=>true),
			array('hostname', 'length', 'max'=>64),
			array('id, server_id', 'safe', 'on'=>'search'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'server_id'		    => 'Server ID',
			'map'				=> 'Map',
			'nextmap'			=> 'Next Map',
			'players'			=> 'Players',
			'maxplayers'		=> 'Max Players',
			'timeleft'			=> 'Timeleft',
			'hostname'			=> 'Name'
		);
	}
}