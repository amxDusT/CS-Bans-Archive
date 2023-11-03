<?php
/**
 * Контроллер админов серверов
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */
class AmxadminsController extends Controller
{
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			//'accessControl',
			'postOnly + delete',
		);
	}


	/**
	 * Детали админа.
	 */
	public function actionView($id=NULL)
	{
		if(!Yii::app()->request->isAjaxRequest && $id !== NULL)
		{
			// Проверка прав
			if(!Webadmins::checkAccess('bans_edit'))
				throw new CHttpException(403, "You do not have enough rights");

			$model = $this->loadmodel($id);
			$this->render('view', array('model' => $model));
			Yii::app()->end();
		}
		//$model = Amxadmins::model()->with('servers')->findByPk($_POST['aid']);
		if( isset( $_POST['aid'] ) )
			$model = Amxadmins::model()->findByPk($_POST['aid']);
		else
			$model = null;
		if($model === null)
		{
			Yii::app()->end("alert('Error!')");
		}

		$steam = '';

		// Если стимайди админа проходит проверку на валидность, получаем инфу об админе с вальве
		if( !empty($model->steamid) && Prefs::validate_value($model->steamid))
		{
			$steamurl = Prefs::steam_convert($model->steamid, false, true);
			if( $steamurl && $url = @file_get_contents($steamurl))
			{
				$xmlres = simplexml_load_string($url);
				$steam = CHtml::image($xmlres->avatarIcon) . " " . CHtml::link(
					$xmlres->steamID,
					"http://steamcommunity.com/profiles/" . $xmlres->steamID64,
					array(
						"target" => "_blank",
						"rel" => "tooltip",
						"title" => "View profile"
					)
				);
			}
		}
		// Формируем таблицу с инфой об админе
		$info = "<table class=\"table table-bordered\">";
		$info .= "<tr>";
		$info .= "<td><b>Nick</b></td>";
		$info .= "<td>".CHtml::encode($model->username)."</td>";
		$info .= "</tr><tr>";
		//$info .= "<td><b>Contacts</b></td>";
		//$info .= "<td>" . ($model->icq ? CHtml::image("//icq-rus.com/icq/3/".$model->icq.".gif"). " " . $model->icq : 'Not set') . "</td>";
		//$info .= "</tr><tr>";
		$info .= "<td><b>Access</b></td>";
		$info .= "<td>".Amxadmins::GetMyFlags($model->access)." (".$model->access.")</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Added</b></td>";
		$info .= "<td>".date("d.m.Y", $model->created)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Expires</b></td>";
		$info .= "<td>" . ($model->expires != 0 ? date("d.m.Y", $model->expires)." (".Amxadmins::GetDays($model->expires).")" : "Never") . "</td>";
		$info .= "</tr>";
		$info .= "</table>";
		$js  = "$('#adminInfo').html('".$info."');";
		$js .= "$('#adminSteam').html('".($steam ? $steam : '<i>Information is absent</i>')."');";
		$js .= "$('#loading').hide();";
		$js .= "$('#adminDetail').modal('show');";
		// Выводим инфу
		Yii::app()->end($js);
	}

	/**
	 * Добавить нового админа
	 */
	public function actionCreate()
	{
		if(!Webadmins::checkAccess('bans_edit'))
			throw new CHttpException(403, "You do not have enough rights");

		$model=new Amxadmins;

		// Аякс проверка формы
		$this->performAjaxValidation($model);

		if(isset($_POST['Amxadmins']))
		{
			$model->attributes=$_POST['Amxadmins'];
			if(isset($_POST['Webadmins']) && $model->validate())
			{
				$wa = new Webadmins;
				$wa->attributes = $_POST['Webadmins'];
				$wa->username = $_POST['Amxadmins']['username'];
				$wa->save();
			}

			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Редактировать админа
	 * @param integer $id ID админа
	 */
	public function actionUpdate($id)
	{
		if(!Webadmins::checkAccess('bans_edit'))
			throw new CHttpException(403, "You do not have enough rights");

		$model=$this->loadModel($id);

		// Аякс проверка формы
		$this->performAjaxValidation($model);

		if(isset($_POST['Amxadmins']))
		{
			$model->attributes=$_POST['Amxadmins'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Удаление админа
	 * @param integer $id ID админа
	 */
	public function actionDelete($id)
	{
		if(!Webadmins::checkAccess('bans_edit'))
			throw new CHttpException(403, "You do not have enough rights");

		$this->loadModel($id)->delete();

		// Если не аякс запрос, то перенаправляем
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Главная страница админов серверов
	 */
	public function actionIndex()
	{
		$this->layout='//layouts/column1';

		// Вытаскиваем админов через датапровайдер
		$dependecy = new CDbCacheDependency('SELECT MAX(`created`), MAX(`expires`) FROM {{admins}}');

		$admins=new CActiveDataProvider(Amxadmins::model()->cache(300, $dependecy), array(
			'criteria'=>array(
				// Выводим только тех, кого разрешено ваыводить
				'condition' => "`access`!='z' AND (`expires` = 0 OR `expires` > UNIX_TIMESTAMP())",
				'order' => '(expires = 0), `expires` ASC, LENGTH(`access`) DESC, `username` ASC',
			),
			'pagination' => array(
				'pageSize' => Yii::app()->config->bans_per_page,

			)
		));
		//$servers = new CActiveDataProvider('Serverinfo');
		$this->render('index',array(
			'admins'=>$admins,
			//'servers'=>$servers,
		));
	}

	/**
	 * Управление админами серверов
	 */
	public function actionAdmin()
	{
		if(!Webadmins::checkAccess('webadmins_view'))
			throw new CHttpException(403, "You do not have enough rights");

		$model=new Amxadmins('search');
		$model->unsetAttributes();
		if(isset($_GET['Amxadmins']))
			$model->attributes=$_GET['Amxadmins'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionSendreset()
	{
		$error = false;
		if( isset($_POST['Amxadmins']))
		{
			$post = $_POST['Amxadmins'];
			$model = Amxadmins::model()->findByAttributes(array('email'=>$post['email']));
			if( $model === null )
			{
				$model = Amxadmins::model();
				
				$model->addError('email','Email not found.');
			}
			else if( $model->reset_expiration > time() )
			{
				$model->addError('email','Reset email already sent. Wait at least 1 hour before trying again.');
			}
			else
			{
				$model->reset_code = Amxadmins::GetRandomCode();
				$model->reset_expiration = time() + 3600;
				$mail = Yii::createComponent('application.extensions.mailer.EMailer');
				Yii::app()->user->setFlash( 'success', 'Password reset email sent. Check your email!' );
				$url = Yii::app()->getBaseUrl(true) . "/amxadmins/reset?code=".$model->reset_code;
				$name = $model->username;
				$mail = Yii::createComponent('application.extensions.mailer.EMailer');
				/**-------------------------------- */
				try {
					$mail->isSMTP();

					$mail->Host = 'mail.nfoservers.com';
					$mail->SMTPAuth = true;
					$mail->Username = 'support@csamx.net';
					$mail->Password = '%CiccioGamer$3';
					$mail->SMTPSecure = 'tls';
					$mail->Port = 587;
					$mail->setFrom('support@csamx.net', 'AmX Gaming');
					$mail->addAddress($model->email);

					// Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = 'Password Reset';
					$mail->Body    = 'Password Reset Request for Nick: ' . htmlspecialchars($name) . '<br>
									If you didn\'t request for a password reset, just ignore this email.<br>
									Follow to be able to reset your password (Link will expire in 1 hour: <a href="'.$url.'">' . $url . '</a><br>
									<br><br>For any problem, write to this email. <br> Thank you, AmX Gaming Staff.';
					$mail->AltBody = 'Password Reset Request for Nick: ' . $name .'. Link will expire in 1 hour: '.$url.'.
									For any problem, write to this email. <br> Thank you, AmX Gaming Staff.';

					$mail->send();
					echo '<div class="nickresult">Password reset mail sent. Check your email.</div>';
				} catch (Exception $e) {
					echo "Message could not be sent. Contact Staff members.";
				}
				/**-------------------------------- */
				if($model->save())
					$this->redirect('index');
			}
		}
		$this->render('sendreset', array(
			'model'=> isset($model) && $model!==null? $model:Amxadmins::model(),
			'error'=> $error
		));
	}

	public function actionReset()
	{
		if( !isset($_GET['code']) || empty($_GET['code']))
		{
			throw new CHttpException(404, 'The requested page does not exist');
		}
		$code = $_GET['code'];
		$model = Amxadmins::model()->findByAttributes(array('reset_code'=>$code));
		if($model === null )
			throw new CHttpException(404, 'The requested reset does not exist');
		
		if($model->reset_expiration < time() )
			throw new CHttpException(404, 'The requested reset has expired.');

		if( isset($_POST['Amxadmins'] ) )
		{
			$post = $_POST['Amxadmins'];
			$model->password = $post['password'];
			$model->password = md5( md5($post['password']) . 'H@Sh4mX%' );
			$model->reset_expiration = 1;
			Yii::app()->user->setFlash( 'success', 'Password successfully changed!' );
			if($model->save())
				$this->redirect('index');
		}
		$this->render('reset', array(
			'model'=>$model
		));
	}

	/**
	 * Загрузка модели по ID
	 * Если не найдено, выводим эксепшн
	 * @param integer ID админа
	 */
	public function loadModel($id)
	{
		$model=Amxadmins::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist');
		return $model;
	}

	/**
	 * AJAX проверка формы
	 */
	protected function performAjaxValidation($model, $id='amxadmins-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax']===$id)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
