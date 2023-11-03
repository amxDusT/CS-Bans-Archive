<?php

class Clans extends CActiveRecord
{
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'db_clans';
	}

    public function rules()
	{
		return array(
			array('clan_name, clan_tag, clan_struct', 'required'),
            array('clan_name', 'length', 'max'=>64),
            array('clan_tag', 'length', 'max'=>12),
            array('clan_struct', 'length', 'max'=>32),
			array('id, clan_name, clan_tag, clan_struct', 'safe', 'on'=>'search'),
		);
	}



	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'clan_name'		    => 'Clan Name',
            'clan_tag'			=> 'Clan Tag',
            'clan_struct'       => 'Clan Structure'
		);
	}

    public function afterDelete() {
		Syslog::add('Deleted Clan', 'Removed clan <strong>' . $this->clan_name . '</strong>');
		return parent::afterDelete();
	}

	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add('Added Clan', 'Added new clan <strong>' . $this->clan_name . '</strong>');
        } else {
            Syslog::add('Edited Clan', 'Clan details changed <strong>' . $this->clan_name . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if($this->clan_name && Clans::model()->count('`clan_name` = :id', array(
					':id' => $this->clan_name
				)))
			{
				return $this->addError($this->clan_name, 'This clan is already in the database.');
			}
		}

		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.clan_name',$this->clan_name);

		$criteria->order = '`clan_name` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}
}
