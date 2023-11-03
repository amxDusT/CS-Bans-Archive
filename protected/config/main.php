<?php
/**
 * Конфигурация приложения
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

/**
 * Класс-заглушка, чтобы нормально использовать переменную $config из старого AmxBans
 */
class conf
{
	public $db_host = null;
	public $db_user = null;
	public $db_pass = null;
	public $db_db = null;
	public $db_prefix = null;
	public $robo_login = null;
	public $robo_pass1 = null;
	public $robo_pass2 = null;
	public $robo_testing = FALSE;
	public $code = NULL;
}
$config = new conf;

// Подключаем конфиг старого AmxBans
require_once ROOTPATH . '/include/db.config.inc.php';

// Подключаем bootstrap
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

$dirs = scandir(dirname(__FILE__).'/../modules');

$modules = array();
foreach ($dirs as $name){
	if ($name[0] != '.') {
		$modules[$name] = array('class'=>'application.modules.' . $name . '.' . ucfirst($name) . 'Module');
	}
}

define('MODULES_MATCHES', implode('|', array_keys($modules)));

// Главные параметры приложения
return array(
	'basePath'=>ROOTPATH . DIRECTORY_SEPARATOR . 'protected',
	'name'=>'AmX CSBans',
	'sourceLanguage' => 'en',
	'language'=>'en',

	// Предзагружаемые компоненты
	'preload'=>array(
		'log',
		'DConfig',
		'Ip2Country',
		),
	// Автозагружаемые модели и компоненты
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.gameq.*',
		'ext.editable.*',

		'application.components.gameq.Protocols.*',
		'application.components.gameq.Exception.*',
		'application.components.gameq.Filters.*',
		'application.components.gameq.Query.*'
	),
	
	'modules'=>array_replace($modules, array(
		
	)),

	// Компоненты приложения
	'components'=>array(
		// Бутстрап
		'bootstrap'=>array(
			'class'=>'bootstrap.components.Bootstrap',
		),
		// Компонент пользователей
		'user'=>array(
			// Аутентификация по куки
			'allowAutoLogin'=>true,
		),
		// Конфиг (из таблицы {{webconfig}})
		'config'=>array(
			'class' => 'DConfig'
		),
		'IpToCountry'=>array(
			'class' => 'Ip2Country'
		),
		// ЧПУ
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix'=>'.html',
			'caseSensitive' => false,
			'rules'=>array(
				'/'=>'bans/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'phpseclib' => array(
			'class' => 'ext.phpseclib.PhpSecLib'
		),
		'mailer' => array(
			'class' => 'application.extensions.mailer.EMailer',
		 ),
		'format'=>array(
			'booleanFormat'=>array('No', 'Yes'),
			'datetimeFormat'=>'d.m.Y H:i',
		),

		// Подключение к БД
		'db'=>array(
			'connectionString' => 'mysql:host='.$config->db_host.';dbname='.$config->db_db,
			'emulatePrepare' => true,
			'username' => $config->db_user,
			'password' => $config->db_pass,
			'charset' => 'utf8',
			'tablePrefix'=>$config->db_prefix.'_',
			'autoConnect' => FALSE,
			'schemaCachingDuration' => 1000,
		),
		'cache'=>array(
			//'class'=>'system.caching.CDummyCache',
			'class'=>'system.caching.CFileCache',
		),
		// Обработка ошибок
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		// Системный лог
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// Раскомментировать, если хотите, чтобы ошибки были выведены на страницах
				//array(
				//	'class'=>'CWebLogRoute',
				//),
			),
		),
	),

	// Тема (темы лежат в themes)
	'theme'=>'dark',

	'homeUrl' => array('/bans/index'),

	// Дополнительные параметры (вызываются так: Yii::app()->params['adminEmail'])
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
		'dbname' => $config->db_db,
		'Version' => '1.3',
	),
);
