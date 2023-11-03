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
require 'SourceQuery/bootstrap.php';

use xPaw\SourceQuery\SourceQuery;
class Servers extends CActiveRecord
{
	public $info = null;
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'servers';
	}

    public function rules()
	{
		return array(
			array('address, port, last_update', 'required'),
			array('address', 'match', 'pattern' => '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'),
			array('port', 'numerical', 'integerOnly'=>true),
			array('port', 'match', 'pattern' => '/^270[0-9][0-9]$/'),
			array('stop_cmd, start_cmd', 'length','max'=>2000),
			array('id, hostname, address, port', 'safe', 'on'=>'search'),
		);
	}


	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'address'		    => 'Server IP',
			'port'				=> 'Server Port',
			'stop_cmd'			=> 'Stop Command',
			'start_cmd'			=> 'Start Command'
		);
	}

    public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Removed server <strong>' . $this->hostname . '</strong>');
		return parent::afterDelete();
	}

	protected function beforeSave() {
		return parent::beforeSave();
	}


	public function afterSave() {
		if ($this->isNewRecord) {
			Syslog::add( "Added Server", 'Added new server <strong>' . $this->hostname . '</strong>');
        } 
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if (!filter_var($this->address, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4))) {
                return $this->addError($this->address, 'Invalid Server IP');
            }

            if($this->address && $this->address && self::model()->count('`address` = :ip AND `port` = :port', array(
					':ip' => $this->address,
					':port' => $this->port
				)))
			{
				return $this->addError($this->address, 'This server is already added.');
			}
			if( empty( $this->port ) )
				$this->port = 27015;
			$this->last_update = 0;
			$this->hostname = 'Not Found';
		}
		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.hostname',$this->hostname);
		$criteria->addSearchCondition('t.address',$this->address);
		$criteria->addSearchCondition('t.port',$this->port);
		$criteria->addSearchCondition('t.stop_cmd',$this->stop_cmd);
		$criteria->addSearchCondition('t.start_cmd',$this->start_cmd);

		$criteria->order = '`id` ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}

	protected function GetInfoData()
	{
		if( $this->last_update + 300 > time() )	// 300 = 5 mins
		{
			$Info = ServersData::model()->findByAttributes(array('server_id'=>$this->id) );
			if( $Info !== null )
			{
				$Players = ServersPlayerData::model()->findAllByAttributes(array('server_id'=>$this->id) );
				//$Players = $Players);
				if( $Info !== null )
				{
					$this->info = $Info;
					if($Players === null )
						$Players = array();
					$this->info->playersinfo = $Players;
				}
				return $this->info;
			}
			
		}

		$server = new SourceQuery();

		try
		{
			$server->Connect( $this->address, $this->port, 3, SourceQuery::GOLDSOURCE );
			
			$Info    = $server->GetInfo( );
			$Players = $server->GetPlayers( );
			$Rules   = $server->GetRules( );
		}
		catch( Exception $e )
		{
			$Exception = $e;
			
		}
		finally
		{
			$server->Disconnect( );
		}
		if( isset($Info) )
		{
			$this->info = ServersData::model()->findByAttributes(array('server_id'=>$this->id) );
			if($this->info === null)
				$this->info = new ServersData();
			$this->info->server_id = $this->id;
			$this->info->players = $Info['Players'];
			$this->info->maxplayers = $Info['MaxPlayers'];
			$this->info->hostname = $Info['HostName'];
			$this->info->map = $Info['Map'];

			$this->info->timeleft = $Rules['mp_timeleft']? $Rules['mp_timeleft']:(isset($Rules['amx_timeleft'])? $Rules['amx_timeleft']:"0");
			$this->info->nextmap = isset($Rules['amx_nextmap'])? $Rules['amx_nextmap']:'None';
			
			$p = ServersPlayerData::model()->deleteAll('server_id = :srv_id', array(':srv_id' => $this->id));
			$p = array();
			foreach( $Players as $player )
			{
				$temp = new ServersPlayerData();
				$temp->server_id = $this->id;
				$temp->nick = $player['Name'];
				$temp->score = $player['Frags'];
				$temp->playedtime = $player['TimeF'];
				$temp->save();
				$p[] = $temp;
			}
			$this->info->playersinfo = $p;
			$this->hostname = $this->info->hostname;
			$this->last_update = time();
			$this->save();
			$this->info->save();
			
		}
		else
		{
			return array(
					'players'=>'Offline',
					'maxplayers'=>'Offline',
					'hostname'=>'Offline',
					'map'=>'Offline',
					'playersinfo'=> array(),
					'timeleft' =>'Offline',
					'nextmap' => 'Offline',
					
			);
		}	

		return $this->info;
	}
	public function GetInfo()
	{
		if( !isset($this->info['map'] ) || strcasecmp($this->info['map'], 'undefined') == 0 )
			return $this->GetInfoData();
		return $this->info;
	}
	public function GetConnect()
	{
		return 'steam://connect/' . $this->address . ':' . $this->port . '/';
	}
}
