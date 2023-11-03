<?php

class Clanmembers extends CActiveRecord
{
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'db_clanmembers';
	}

    public function rules()
	{
		return array(
			array('clanid, player_name, is_owner', 'required'),
            array('player_name', 'length', 'max'=>64),
			array('id, clanid, player_name, is_owner', 'safe', 'on'=>'search'),
		);
	}

    public function relations()
	{
		return array(
			'clan_id' => array(self::BELONGS_TO, 'Clans', 'id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
            'clanid'		    => 'Clan ID',
            'player_name'       => 'Player Nick',
            'is_owner'			=> 'Clan Owner'
		);
	}

    public function afterDelete() {
		Syslog::add('Del Clan Member', 'Removed clan member <strong>' . $this->player_name . '</strong>');
		return parent::afterDelete();
	}

	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add('Added Clan Member', 'Added new clan member <strong>' . $this->player_name . '</strong>');
        } else {
            Syslog::add('Edited Clan Member', 'Clan member details changed <strong>' . $this->player_name . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if($this->player_name && Clanmembers::model()->count('`player_name` = :id', array(
					':id' => $this->player_name
				)))
			{
				return $this->addError($this->player_name, 'This clan is already in the database.');
			}
		}

		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.player_name',$this->player_name);
		$criteria->addSearchCondition('t.clanid',$this->clanid);

		$criteria->order = '`is_owner` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}

	public static function getClanName($cid)
	{
		$m = Clans::model()->find('`id` = :id', array(':id' => $cid));
		if($m !== NULL)
			return $m->clan_name;
		else
			throw new CHttpException(404, "Clan ".$cid." not found");
	}
}
