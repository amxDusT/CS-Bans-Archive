<?php

class ClanmembersController extends Controller
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

		if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '');
        }
    }

	public function actionIndex()
	{
        $model = Clanmembers::model();
		$model->unsetAttributes();
		if (isset($_GET['Clanmembers'])) {
			$model->attributes = $_GET['Clanmembers'];
        }

		$dataProvider=new CActiveDataProvider('Clanmembers', array(
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`is_owner` DESC,`id` DESC',
                    'attributes' => array(
						'is_owner',
						'id'
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

		if(isset($_POST['Clanmembers'])) {

			$model->attributes=$_POST['Clanmembers'];

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
		
        $model = new Clanmembers;

		$this->performAjaxValidation($model);

		if(isset($_POST['Clanmembers'])) {
			$model->attributes=$_POST['Clanmembers'];
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
		$model=Clanmembers::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='clanmembers-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
