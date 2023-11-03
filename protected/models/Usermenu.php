<?php
/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

/**
 * Модель для таблицы "{{usermenu}}".
 *
 * Доступные поля таблицы '{{usermenu}}':
 * @property integer $id ID записи
 * @property integer $pos Позиция
 * @property integer $activ Активность
 * @property string $lang_key Анкор для гостя
 * @property string $url Ссылка для гостя
 * @property string $lang_key2 Анкор для админа
 * @property string $url2 Ссылка для админа
 */
class Usermenu extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{usermenu}}';
	}

	public function rules()
	{
		return array(
			array('pos, activ', 'numerical', 'integerOnly'=>true),
			array('pos', 'unique'),
			array('lang_key, url, lang_key2, url2', 'length', 'max'=>64),
			array('id, pos, activ, lang_key, url, lang_key2, url2', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'pos' => 'Position',
			'activ' => 'Activity',
			'lang_key' => 'Name for Guests',
			'url' => 'URL for Guests',
			'lang_key2' => 'Name for Admins',
			'url2' => 'URL For Admins',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('pos',$this->pos);
		$criteria->compare('activ',$this->activ);
		$criteria->compare('lang_key',$this->lang_key,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('lang_key2',$this->lang_key2,true);
		$criteria->compare('url2',$this->url2,true);
		$criteria->order = '`pos` ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getMenu( $check = false )
	{
		if(
				in_array(Yii::app()->controller->action->id, array('install', 'license'))
					||
				defined('NOREDIRECT')
		) {
			return array(
				array(
					'label' => 'Home',
					'url' => '../',
				)
			);
		}
		elseif(!Yii::app()->db->username) {
			Yii::app()->controller->redirect(array('/site/install'));
		}

		// Получаем модель
		$model = self::model()->findAll('`activ` = 1');

		// Гость?
		$guest = Yii::app()->user->isGuest;

		// Задаем меню массив
		$menu = array();

		// Проверки на ланг ключи (чтобы не было проблем со ссылками после обновления)
		$match = array(
			'_HOME' => 'Home',
			'_BANLIST' => 'Banlist',
			'_SERVER' => 'Servers',
			'_ADMLIST' => 'Admins',
			'_SEARCH' => 'Search',
			'_LOGIN' => 'Login',
			'_LOGOUT' => 'Logout',
		);
		$index = 0;
		foreach ($model as $m)
		{
			// Пропускаем неактивные ссылки
			//if($m->activ !== '1') continue;

			// Задаем урл для гостей и админов
			$url = $guest ? $m->url : $m->url2;

			// Если ссылка внутренняя, выводим через юии
			if(!filter_var($url, FILTER_VALIDATE_URL))
				$url = Yii::app()->createUrl($url);

			// Задаем анкор для гостей и админов
			$key = $guest ? $m->lang_key : $m->lang_key2;

			// Если анкора нет, не выводим ссылку
			if(!$key || empty($key)) continue;

			// Если анкоры прописаны ланг ключами, то подменяем ключи на слова
			if(array_key_exists($key, $match))
				$key = $match[$key];

			// Формируем массив для меню
			$index++;
			if( ( $index > 6 && $check == true ) || ($index <= 6 && $check == false ) )
			{
				$menu[] = array(
					'label' => $key,
					'url' => $url
				);
			}
			
			if( $index > 6 && $check == false )
				break;
		}

		// Возвращаем меню
		return $menu;
	}

	public static function getPositions()
	{
		$count = Usermenu::model()->count();
		$return = array();
		for($i=1; $i<=$count + 1; $i++)
		{
			$return[$i] = $i;
		}

		return $return;
	}

	public function afterSave() {
		if($this->isNewRecord)
			Syslog::add(Logs::LOG_ADDED, 'Add new menu link <strong>' . $this->id . '</strong>');
		else
			Syslog::add(Logs::LOG_EDITED, 'Link Changed <strong>' . $this->id . '</strong>');
		return parent::afterSave();
	}

	public function afterDelete() {
		Syslog::add(Logs::LOG_DELETED, 'Link Removed <strong>' . $this->id . '</strong>');
		return parent::afterDelete();
	}
}