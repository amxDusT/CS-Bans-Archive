<?php
/**
 * Контроллер админцентра
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

class AdminController   extends Controller
{

    public $layout='//layouts/main';

    public function filters()
    {
		return array(
			'accessControl',
			'postOnly + delete',
			'ajaxOnly + actions',
		);
    }

	public function actionVersion() {
		if(isset($_POST['version']))
			Yii::app ()->end(Prefs::getVersion());
		Yii::app ()->end('1');
	}



	public function actionActions()
	{
		if(!Webadmins::checkAccess('prune_db'))
		{
			Yii::app()->end("$('#loading').hide();alert('You do not have enough rights!');");
			return; 
		}
			

		switch ($_POST['action'])
		{
			case 'truncatebans':
				Yii::app()->db->createCommand()->truncateTable('{{bans}}');
				Yii::app()->end("$('#loading').hide();alert('Ban table cleared successfully!');");

			case 'clearcache':
				$dir = ROOTPATH."/assets";
				self::removeDirRec($dir);
				Yii::app()->cache->flush();
				Yii::app()->end("$('#loading').hide();alert('Cache is cleared!');");

			case 'optimizedb':
				$query = Yii::app()->db->createCommand("SHOW TABLES FROM `" . Yii::app()->params['dbname']. "` LIKE '".Yii::app()->db->tablePrefix."%'")->queryAll();
				$tables = "";
				foreach($query as $tmp) {
					foreach ($tmp as $key=>$val)
						$tables.=($tables != "" ? "," : "")."`".$val."`";
				}
				$optimize = Yii::app()->db->createCommand("OPTIMIZE TABLES ".$tables)->query();
				$alert = $optimize ? "Base is Optimized!" : "Optimization error";
				Yii::app()->end("$('#loading').hide();alert('". $alert."');");

			case 'optimizebanstable':
				//$query=mysql_query("SELECT ba.bid,ba.ban_created,ba.ban_length,se.timezone_fixx FROM ".$config->db_prefix."_bans as ba
				//			LEFT JOIN ".$config->db_prefix."_serverinfo AS se ON ba.server_ip=se.address WHERE ba.expired=0");
				$query = Yii::app()->db->createCommand()
						->select("ba.bid,ba.ban_created,ba.ban_length,se.timezone_fixx")
						->from('{{bans}} ba')
						->leftJoin("{{serverinfo}} se", "ba.server_ip=se.address")
						->where("ba.expired=0")->queryAll(true);

				$prunecount="";

				foreach($query as $tmp=>$val)
				{
					//foreach($tmp as $result=>$val)
					//{
						$prunecount.= $val;
					//}
				}

				//($result = $query) {
					//prune expired bans
					/*
					if(($result->ban_created + ($result->timezone_fixx * 60 * 60) + ($result->ban_length * 60)) < time() && $result->ban_length != "0") {
						$prunecount++;
						$prune_query = mysql_query("UPDATE `".$config->db_prefix."_bans` SET `expired`=1 WHERE `bid`=".$result->bid);
						$prune_query = mysql_query("INSERT INTO `".$config->db_prefix."_bans_edit` (`bid`,`edit_time`,`admin_nick`,`edit_reason`) VALUES (
										'".$result->bid."','".($result->ban_created + ($result->timezone_fixx * 60 * 60) + ($result->ban_length * 60))."','amxbans','Bantime expired')");
					}
					 *
					 */
					//$prunecount[] = $result;
				//}
				Yii::app()->end("$('#loading').hide();alert('". $prunecount['']."');");
		}
	}

	/**
	 * Главная страница админцентра
	 * @throws CHttpException
	 */
	public function actionIndex()
    {
		// Если гость, выдаем эксепшн
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, 'You do not have enough rights');

        $this->render(
			'index',
			array(
				'sysinfo'=>array(
					// Всего банов
					'bancount' => Bans::model()->cache(300)->count(),
					// Активные баны
					'activebans' => Bans::model()->cache(300)->count(),
				)
			)
		);
    }

	/**
	 * Управление серверами
	 * @throws CHttpException
	 */
    public function actionServers()
    {
		if(Yii::app()->user->isGuest)
			throw new CHttpException(403, 'You do not have enough rights');

		$model=new Serverinfo('search');
		$model->unsetAttributes();
		if(isset($_GET['Serverinfo']))
			$model->attributes=$_GET['Serverinfo'];

        $servers=new CActiveDataProvider('Serverinfo', array(
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page,
			))
		 );
        $this->render('servers',array(
			'servers'=>$servers,
			'model' => $model,
        ));
    }

	/**
	 * Настройки сайта
	 * @throws CHttpException
	 */
	public function actionWebsettings()
	{
		// Проверяем права пользователя
		if(!Webadmins::checkAccess('websettings_view'))
			throw new CHttpException(403, "You do not have enough rights");

		// Вытаскиваем модель
		$model =  Webconfig::getCfg();

		$themes = array();

		// Ищем папки тем в themes
		foreach(glob(ROOTPATH . '/themes/*') as $t)
		{
			$themes[basename($t)] = basename($t);
		}

		if(isset($_POST['Webconfig']))
		{
			if(!Webadmins::checkAccess('websettings_edit'))
				throw new CHttpException(403, "You do not have enough rights");

			$model->attributes=$_POST['Webconfig'];
			if($model->save())
				$this->redirect(array('websettings'));
		}

		$this->render('websettings',array(
			'model'=>$model,
			'themes'=>$themes,
		));
	}

	/**
	 * Удаляет файлы и папки рекурсивно из папки $dir
	 * @param string $dir Имя папки, в которой нужно удалить файлы и папки рекурсивно
	 */
	private function removeDirRec($dir)
	{
		if ($objs = glob($dir."/*")) {
			foreach($objs as $obj) {
				if(basename($obj)=='.' || basename($obj)=='..')
					continue;
				if(is_dir($obj))
				{
					self::removeDirRec($obj);
					@rmdir($obj);
				}
				else
					@unlink($obj);
			}
		}
	}
}

?>
