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
class Chatmanager extends CActiveRecord
{
    public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function tableName()
	{
		return 'db_patterns';
	}

    public function rules()
	{
		return array(
			array('pattern, block_type', 'required'),
			array('time', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>64),
			array('id, pattern, block_type, reason, time', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'				=> 'ID',
			'block_type'		=> 'Block Type',
			'time'		        => 'Time',
			'reason'            => 'Reason',
		);
	}

    public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Removed CM pattern <strong>' . $this->pattern . '</strong>');
		return parent::afterDelete();
	}

	public function afterSave() {
		if ($this->isNewRecord) {
            Syslog::add(Logs::LOG_ADDED, 'Added new pattern <strong>' . $this->pattern . '</strong>');
        } else {
            Syslog::add(Logs::LOG_EDITED, 'Pattern details changed <strong>' . $this->pattern . '</strong>');
        }
        return parent::afterSave();
	}

	protected function beforeValidate() {
		if($this->isNewRecord) {
			if($this->pattern && Chatmanager::model()->count('`pattern` = :id', array(
					':id' => $this->pattern
				)))
			{
				return $this->addError($this->pattern, 'This pattern is already in the database.');
			}
		}

		return parent::beforeValidate();
	}

    public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->addSearchCondition('t.pattern',$this->pattern);
		$criteria->addSearchCondition('t.block_type',$this->block_type);
		$criteria->addSearchCondition('t.reason',$this->reason);
        $criteria->addSearchCondition('t.time',$this->time);

		$criteria->order = '`id` DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page
			)
		));
	}

	public static function getBlockTypes()
	{
		return array(
			"0" => "Kick",
			"1" => "Ban",
			"2" => "Hide",
			"3" => "Whitelist",
			"4" => "Replace"
		);
	}
}
