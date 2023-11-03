<?php

class ConnectlogsController extends Controller
{
	public $layout='//layouts/column1';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete'
		);
	}

	public function actionIndex()
	{
		if (!Webadmins::checkAccess('cl_view')) {
            throw new CHttpException(403, "You do not have enough rights");
		}
		//$model=new Gags('search');
        $model = Connectlogs::model();
		$model->unsetAttributes();
		if (isset($_GET['Connectlogs'])) {
			$model->attributes = $_GET['Connectlogs'];
			if(empty($model->ip) && empty($model->steamid) && empty($model->nick) )
				$model->isEmpty = true;
		}
		
		$dataProvider=new CActiveDataProvider('Connectlogs', array(
            'criteria' => array(
                'select' => 't.*, max(date) as tdate, sum(played_time) as pl',
				'group' => 't.nick',
				//'having'=>'t.nick=tnick AND t.date=tdate',
				'order' => 'max(date) DESC',
				

            ),
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'attributes' => array(
                        'nick',
                        'ip',
                        'steamid',
                        'tdate',
                        'played_time'
                    )
                )
            )
         );

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model
		));

	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		if (!Webadmins::checkAccess('cl_view')) {
            throw new CHttpException(403, "You do not have enough rights");
		}
		$history = new CActiveDataProvider('Connectlogs', array(
            'criteria' => array(
				'condition'=>'`id`<>:id AND (t.nick=:nick OR t.steamid=:steamid OR t.ip=:ip)',
				'params' => array(
					':id' => $id,
					':nick' => $model->nick,
					':steamid' => $model->steamid,
					':ip' => $model->ip
				),
                'select' => 't.*, max(date) as tdate',
				'group' => 't.nick,t.steamid,t.ip',
				//'having'=>'t.nick=tnick AND t.date=tdate',
				'order' => 'tdate DESC',
            ),
			'pagination' => array(
				'pageSize' =>  5,
            )
		));
		$this->render('view',array(
			'model'=>$model,
			'history' => $history
		));
	}
	
	public function loadModel($id)
	{
		$model=Connectlogs::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='connect-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
