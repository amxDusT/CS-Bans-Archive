<?php

class ChatmanagerController extends Controller
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
		if (!Webadmins::checkAccess( 'cm_view' )) {
            throw new CHttpException(403, "You do not have enough rights");
        }
        $model = Chatmanager::model();
		$model->unsetAttributes();
		if (isset($_GET['Chatmanager'])) {
            $model->attributes = $_GET['Chatmanager'];
		}
		else
			$model->block_type = 0;

		$dataProvider=new CActiveDataProvider('Chatmanager', array(
			'criteria' => array(
				'condition'=>'block_type='.$model->block_type,
			),
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`id` DESC',
                    'attributes' => array(
                        'pattern',
                        'block_type',
                        'time',
                        'reason'
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
		

		// Проверка прав
		if (!Webadmins::checkAccess( 'cm_edit' )) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		$model=$this->loadModel($id);
		// Сохраняем форму
		if(isset($_POST['Chatmanager'])) {

			$model->attributes=$_POST['Chatmanager'];

			if ($model->save()) {
                $this->redirect(array('index?Chatmanager%5Bblock_type%5D='.$model->block_type));
			}
        }
		
		$this->render('update',array(
			'model'=>$model,
		));
	}
	public function actionCreate()
	{
		if (!Webadmins::checkAccess('cm_edit')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Chatmanager;

		$this->performAjaxValidation($model);

		if(isset($_POST['Chatmanager'])) {
			$model->attributes=$_POST['Chatmanager'];
			//print_r( $model );
			if ($model->save()) {
                $this->redirect(array('index?Chatmanager%5Bblock_type%5D='.$model->block_type));
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (!Webadmins::checkAccess('cm_edit')) {
            throw new CHttpException(403, "You don't have enough rights");
        }

        $model->delete();

		if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '');
        }
	}
	
	public function loadModel($id)
	{
		$model=Chatmanager::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cm-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
