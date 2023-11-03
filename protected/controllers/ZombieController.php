<?php

class ZombieController extends Controller
{
	public $layout='//layouts/column1';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete'
		);
	}

	public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction'
            )
        );
    }

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (!Webadmins::checkAccess('zombie_edit')) {
            throw new CHttpException(403, "You don\'t have enough rights");
        }

        $model->delete();

		if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '');
        }
    }

	public function actionIndex()
	{
		
		//$model=new Gags('search');
        $model = Zombie::model();
		$model->unsetAttributes();
		if (isset($_GET['Zombie'])) {
			$model->attributes = $_GET['Zombie'];
			//print_r( $_GET['Zombie']);
        }

		$dataProvider=new CActiveDataProvider('Zombie', array(
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`ammo` DESC',
                    'attributes' => array(
                        'player_nick',
                        'ammo'
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
		if (!Webadmins::checkAccess( 'zombie_edit' )) {
            throw new CHttpException(403, "You do not have enough rights");
        }

		// Сохраняем форму
		if(isset($_POST['Zombie'])) {

			$model->attributes=$_POST['Zombie'];

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
		if (!Webadmins::checkAccess('zombie_edit')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Zombie;

		$this->performAjaxValidation($model);

		if(isset($_POST['Zombie'])) {
			$model->attributes=$_POST['Zombie'];
			
			if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Zombie::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='zombie-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
