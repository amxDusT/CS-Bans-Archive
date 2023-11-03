<?php

class ClansController extends Controller
{
	public $layout='//layouts/column1';

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete'
		);
	}


	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (!Webadmins::checkAccess('bans_edit')) {
            throw new CHttpException(403, "You don\'t have enough rights");
        }

        $model->delete();
        
        Clanmembers::model()->deleteAll('`clanid` = :id', array(':id' => $id));

		if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '');
        }
    }

	public function actionIndex()
	{
		
        $model = Clans::model();
		$model->unsetAttributes();
		if (isset($_GET['Clans'])) {
			$model->attributes = $_GET['Clans'];
        }

		$dataProvider=new CActiveDataProvider('Clans', array(
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`id` ASC',
                    'attributes' => array(
                        'clan_name',
                        'clan_tag'
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

		if (!Webadmins::checkAccess( 'bans_edit' )) {
            throw new CHttpException(403, "You do not have enough rights");
        }

		if(isset($_POST['Clans'])) {

			$model->attributes=$_POST['Clans'];

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
		if (!Webadmins::checkAccess('bans_edit')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Clans;

		$this->performAjaxValidation($model);

		if(isset($_POST['Clans'])) {
			$model->attributes=$_POST['Clans'];
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
		$model=Clans::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='clans-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
