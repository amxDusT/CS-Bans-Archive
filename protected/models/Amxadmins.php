<?php
/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

class Amxadmins extends CActiveRecord
{
	//public $accessflags = array();
	public $change;
	public $addtake = null;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{admins}}';
	}


	public function getAccessflags() {

		return str_split($this->access);
	}

	public function setAccessflags($value) {
		//return false;
	}

	public function scopes()
    {
        return array(
            'sort'=>array(
                'order'=>'`expires` ASC, `username` ASC'
            ),
        );
    }

	public function rules()
	{
		return array(
			array('username, email', 'required'),
			array('expires, change', 'numerical', 'integerOnly'=>true),
			array('addtake', 'in', 'range' => array('0','1','2')),
			array('access, flags, username', 'length', 'max'=>32),
			array('password', 'length', 'max'=>50),
			array('steamid', 'match', 'pattern' => '/^(STEAM|VALVE)_([0-9]):([0-9]):\d{1,21}$/' ),
			array('id, username, steamid, password, access, flags, email, created, expires', 'safe',  'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public static function GetMyFlags( $access = 'z', $show_all = false, $search = false )
	{
		$flags = array(
			'z'=>'Normal',
			'bp'=>'VIP',
			'bipt'=>'Super VIP',
			'bcdeijptuv'=>'Admin',
			'abcdefijptuv'=>'Super Admin',
			'abcdefijprtuv'=>'Dev/Demo Checker',
			'abcdefijprstuv'=>'Server Manager',
			'abcdefghijmpqrstuv'=>'Moderator',
			'abcdefghijkmpqrstuv'=>'Head Moderator',
			'abcdefghijklmnpqrstuv'=>'Administrator',
			'abcdefghijklmnopqrstuvy'=>'Owner'
		);
		if( $show_all )
			return $flags;
		
		if( $search )
		{
			$key = array_search( $access, $flags );
			if( $key === false )
				return $access;
			else
				return $key;
		}
		if( array_key_exists( $access, $flags ) )
			return $flags[ $access ];
		else
			return $access;
	}

	public static function GetDays( $expired = 0 )
	{
		if( $expired == 0 )
			return "Permanent.";
		$diff = $expired - time();
		if( $diff <= 0 )
		{
			return "Expired.";
		}
		$days = round($diff/(60*60*24));
	
		$style = '';
		if( $days < 7 )
			$style = "class=\"leftDanger\"";
		else if( $days <= 15 )
			$style = "class=\"leftRisk\"";
		else
			$style = "class=\"leftOk\"";
		//return "null";
		return "<b $style>".$days."</b> day".($days==1? '':'s')." left"; 		
	}
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Nick',
			'steamid' => 'SteamID',
			'password' => 'Password',
			'access' => 'Privileges',
			'flags' => 'Account Flags',
			'created' => 'Date Added',
			'expires' => 'Expires'
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		//$criteria->compare('steamid',$this->steamid,true);
		$criteria->compare('access',self::GetMyFlags(ucwords($this->access), false, true),true);
		$criteria->compare('flags',$this->flags,true);
		$criteria->compare('expires',$this->expires);
		//$criteria->order = 'expires ASC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page,
			),
		));
	}

	protected function beforeDelete() {
		parent::beforeDelete();
        return true;
	}

	protected function beforeSave() {
		if( isset($_GET['code'] ) )
		{
			return parent::beforeSave();
		}
        $removePwd = filter_input(INPUT_POST, 'removePwd', FILTER_VALIDATE_BOOLEAN);
        if($removePwd) {
            $this->password = '';
        }
        
		if($this->isNewRecord) {
			$this->created = time();
			if( $this->expires != 0 )
				$this->expires = time() + $this->expires * 86400;
            if($this->flags != 'a' && !$this->password) {
                $this->flags .= 'e';
			}
			if( $this->password )
				$this->password = md5( md5($this->password) . "H@Sh4mX%" );
		} else {
			if ($this->password) {
                $this->password = md5( md5($this->password) . "H@Sh4mX%" );
            } else {
                $oldadmin = Amxadmins::model()->findByPk($this->id);
                if ($oldadmin->password && !$removePwd) {
                    $this->password = $oldadmin->password;
                } else if($this->flags != 'a') {
                    $this->flags .= 'e';
                }
            }
			
			if( !$this->change )
				$this->change = 0;
			if( $this->access == 'z' )
				$this->expires = 0;
			else
			{
				switch($this->addtake) {
					case '1':
						$this->expires = $this->expires - ($this->change *86400);
						break;
					case '0':
						if( $this->expires < time() )
							$this->expires = time() + ($this->change *86400);
						else
							$this->expires = $this->expires + ($this->change *86400);
						break;
					default:
						$this->expires = 0;
				}
			}
		}
		return parent::beforeSave();
	}

	protected function afterValidate() {
	
        if (!$this->access) {
            $this->addError('access', 'Select Access Flags');
        }

        if($this->isNewRecord && $this->flags === 'a' && !$this->password) {
            $this->addError('password', 'For admin on nick, you must specify the password');
        }

        if ($this->password && !preg_match('/^([0-9a-zA-Z !\-"#$%&\'()*+,.\/:;<=>?@\\^_`{|}~]+)$/i', $this->password)) {
			$this->addError ('password', 'Password can contain only latin letters and numbers');
		}
        
        if(!$this->isNewRecord && $this->addtake === '1' && ($this->expires - time() ) < $this->change*86400 )
		{
			$this->addError ('', 'mistake! You can’t take more days than he already has');
		}
		
		if( isset($_GET['code']) && strlen($this->password) < 3 )
			$this->addError('password','Password must have at least 3 characters');

        if($this->hasErrors()) {
            return $this->getErrors();
        }
		
		return parent::afterValidate();
	}
	public static function getAuthType($get = false)
	{
		$flags = array(
			'a' => 'Nick',
			'c' => 'SteamID',
			'ca' => 'SteamID + Password',
			'd' => 'IP'
		);
		
		if($get) {
            $flag = $get[0];
			if(isset($flags[$flag])) {
                $return = $flags[$flag];
                if(isset($get[1])&& $get[1]==='a') {
                    $return .= ' + Password';
                }
				return $return;
			}
			return 'Unknown';
		}
		return $flags;
	}

	public function getLong()
	{
		$long = $this->expires - time();
		if ($this->expires == 0 || $long < 0) {
            return false;
        }

        return intval($long / 86400);
	}

	public function afterSave() {

		if ($this->isNewRecord) {
            Syslog::add('Add Nick', 'Added new AMXMODX admin <strong>' . $this->username . '</strong>');
        } else {
            Syslog::add('Edit Nick', 'Changed AMXMODX admin details <strong>' . $this->username . '</strong>');
        }
        return parent::afterSave();
	}

	public function afterDelete() {
        //AdminsServers::model()->deleteAllByAttributes(array('admin_id' => $this->id));
		Syslog::add('Delete Nick', 'Removed AMXMODX Admin <strong>' . $this->username . '</strong>');
		return parent::afterDelete();
	}

	public static function GetRandomCode( $len = 30 ) {
		$arrlet = array_merge( range( 'a', 'z' ),range( 'a', 'z' ), range( '0', '9' ), range( '0', '9' ));
		//$word = $word + $numbers;
		shuffle( $arrlet );
		return substr( implode( $arrlet ), 0, $len );    
	}

}