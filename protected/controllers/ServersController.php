<?php

class ServersController extends Controller
{
	public $layout='//layouts/column1';
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly + sync, start, stop, actionViewserver, info'
		);
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (!Webadmins::checkAccess('servers_edit')) {
            throw new CHttpException(403, "You don't have enough rights");
        }

		ServersData::model()->deleteAll('server_id = :srv_id', array(':srv_id' => $this->id));
		ServersPlayerData::model()->deleteAll('server_id = :srv_id', array(':srv_id' => $this->id));
		
        $model->delete();

		Yii::app()->end('Server Removed');
    }

	public function actionIndex()
	{
        $model = Servers::model();
		$model->unsetAttributes();
		if (isset($_GET['Servers'])) {
            $model->attributes = $_GET['Servers'];
        }
		$dataProvider=new CActiveDataProvider('Servers', array(
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`id` ASC',
                    'attributes' => array(
                        'hostname',
                        'address',
                        'port'
                    )
                )
            )
		 );
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model
		));

	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Проверка прав
		if (!Webadmins::checkAccess('servers_edit' )) {
            throw new CHttpException(403, "You do not have enough rights");
        }

		// Сохраняем форму
		if(isset($_POST['Servers'])) {

			$model->attributes=$_POST['Servers'];

			if ($model->save()) {
                $this->redirect(array('index'));
			}
        }
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		if (!Webadmins::checkAccess('servers_edit')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Servers;

		$this->performAjaxValidation($model);

		if(isset($_POST['Servers'])) {
			$model->attributes=$_POST['Servers'];
			if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionViewserver()
	{
		$model = Servers::model()->findByPk($_POST['aid']);
		if($model === null)
		{
			Yii::app()->end("alert('Error!')");
		}

		$server = $model->GetInfo();

		// Формируем таблицу с инфой об админе
		$info = "<table class=\"table table-bordered\">";
		$info .= "<tr>";
		$info .= "<td><b>Server Name</b></td>";
		$info .= "<td>".CHtml::encode($model->hostname)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Server IP</b></td>";
		$info .= "<td>".CHtml::link( $model->address.":".$model->port, $model->GetConnect() )."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Map</b></td>";
		$info .= "<td>".$server['map']."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Nextmap</b></td>";
		$info .= "<td>".$server['nextmap']."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Timeleft</b></td>";
		$info .= "<td>".$server['timeleft']."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Players</b></td>";
		$info .= "<td>".$server['players']." / ".$server['maxplayers']."</td>";
		$info .= "</tr><tr>";
		$info .= "<td colspan=2 style=\"text-align: center\"><a href=\"".Yii::app()->createUrl('servers/view/'.$model->id )."\" class=\"btn\">Show Players</a></td>";
		$info .= "</tr>";
		$info .= "</table>";
		$js = "$('#serverInfo').html('".$info."');";
		$js .= "$('#loading').hide();";
		$js .= "$('#serverssDetail').modal('show');";
		// Выводим инфу
		Yii::app()->end($js);
	}

	public function actionStart($id)
	{
		$model=$this->loadModel($id);

		// Проверка прав
		if (!Webadmins::checkAccess('servers_start' )) {
            throw new CHttpException(403, "You do not have enough rights");
		}
		
		if( empty( $model->start_cmd ) )
			Yii::app()->end('Start Command missing.');

		$ssh = Yii::app()->phpseclib->createSSH2('185.107.96.138');
		if (!$ssh->login('amx', 'DA4NTEzMDUzO')) {
			Yii::app()->user->setFlash( 'error', 'SSH2 Login Failed.' );
			$this->redirect(Yii::app()->request->urlReferrer);
		}

		$txt = $ssh->exec($model->start_cmd);
		if( strpos( $txt, '[0m] Starting' ) === false )
			Yii::app()->end('Server Start Failed.');
		else if( strpos( $txt, 'already running' ) === false )
			Yii::app()->end('Server Started.');
		else
			Yii::app()->end('Server Already Running.');
	}
	public function actionStop($id)
	{
		$model=$this->loadModel($id);

		// Проверка прав
		if (!Webadmins::checkAccess('servers_stop' )) {
            throw new CHttpException(403, "You do not have enough rights");
		}
		
		if( empty( $model->stop_cmd ) )
			Yii::app()->end('Stop Command missing.');

		$ssh = Yii::app()->phpseclib->createSSH2('185.107.96.138');
		if (!$ssh->login('amx', 'DA4NTEzMDUzO')) {
			Yii::app()->user->setFlash( 'error', 'SSH2 Login Failed.' );
			$this->redirect(Yii::app()->request->urlReferrer);
		}

		$txt = $ssh->exec($model->stop_cmd);
		if( strpos( $txt, '[0m] Stopping' ) === false )
			Yii::app()->end('Server Stop Failed.');
		else if( strpos( $txt, 'already stopped' ) === false )
			Yii::app()->end('Server Stopped.');
		else
			Yii::app()->end('Server Already Stopped.');
	}
	public function actionSync($id)
	{
		$model=$this->loadModel($id);

		// Проверка прав
		if (!Webadmins::checkAccess('servers_start') || !Webadmins::checkAccess('servers_stop')) {
            throw new CHttpException(403, "You do not have enough rights");
		}
		
		if( empty( $model->start_cmd ) || empty( $model->stop_cmd ) )
			Yii::app()->end('Stop or Start Command missing.');
		$ssh = Yii::app()->phpseclib->createSSH2('185.107.96.231');
		if (!$ssh->login('amx', 'jE3NDk5NTc5M')) {
			Yii::app()->user->setFlash( 'error', 'SSH2 Login Failed.' );
			$this->redirect(Yii::app()->request->urlReferrer);
		}

		$ssh->exec($model->stop_cmd);
		$txt = $ssh->exec($model->start_cmd);
		if( strpos( $txt, '[0m] Starting' ) === false )
		{
			Yii::app()->end('Server Restart Failed.');
		}
		else
		{
			Yii::app()->end('Server Restarted.');
		}
	}
	public function actionView($id)
	{

		// Подгружаем баны
		$model=Servers::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
		
		// Вывод всего на вьюху
		$this->render('view',array(
			'model'=>$model
		));
	}
	public function actionInfo()
	{
		$model = Servers::model()->findByPk($_POST['aid']);
		if($model === null)
		{
			Yii::app()->end("alert('Error!')");
		}
		$server = $model->GetInfo();
		$js = "$('#srv_' + ".$_POST['aid']." + ' .map').html('".$server['map']."');";
		$js .= "$('#srv_' + ".$_POST['aid']." + ' .players').html('".$server['players']."');";
		Yii::app()->end($js);
	}
	public function actionList() {
		$criteria = new CDbCriteria();
		// Add your Query condition here
		// $criteria = 'your condition here';
	
		// Create `activeDataProvider` object for Subscriber model
		// NOTE: Subscriber model is not included in this article
		// Replace model name with your's
		$dataProvider = new CActiveDataProvider('Servers', array(
		  'criteria' => $criteria,
		));
	
		$grid_id = 'servers-grid';
	
		// Check is for AJAX request by `subscriber_grid`
		// ajax response only grid content instead entire web page
		if(Yii::app()->request->isAjaxRequest && isset($_GET['ajax']) && $_GET['ajax'] === $grid_id) {
		  // Render partial file created in Step 1
		  $this->renderPartial('___grid_view_forsubscriber', array(
			'dataProvider' => $dataProvider,
			'grid_id' => $grid_id,
		  ));
		  Yii::app()->end();
		}
		
		// Render main view file created in Step 2
		$this->render('subscribers_list', array(
		  'dataProvider' => $dataProvider,
		  'grid_id' => $grid_id,
		));
	
	  }
	public function loadModel($id)
	{
		$model=Servers::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='servers-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}