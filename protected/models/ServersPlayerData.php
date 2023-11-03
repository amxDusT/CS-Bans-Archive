<?php

class ServersPlayerData extends CActiveRecord
{
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'servers_player_data';
	}

    public function rules()
	{
		return array(
			array('server_id, nick, score, playedtime', 'required'),
			array('server_id, score', 'numerical', 'integerOnly'=>true),
			array('id, server_id', 'safe', 'on'=>'search'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'server_id'		    => 'Server ID',
			'nick'				=> 'Nick',
			'score'				=> 'Score',
			'playedtime'		=> 'Played Time',
		);
	}
}