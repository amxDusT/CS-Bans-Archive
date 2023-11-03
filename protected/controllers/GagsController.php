<?php

class GagsController extends Controller
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

		if (!Webadmins::checkAccess('gags_edit', $model->admin_name)) {
            throw new CHttpException(403, "You don\'t have enough rights");
        }

		if(!$model->delete())
			Yii::app()->end("alert('Error!')");

		if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '');
        }
    }

	public function actionIndex()
	{
		//$model=new Gags('search');
        $model = Gags::model();
		$model->unsetAttributes();
		if (isset($_GET['Gags'])) {
            $model->attributes = $_GET['Gags'];
        }

		$dataProvider=new CActiveDataProvider('Gags', array(
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`create_time` DESC',
                    'attributes' => array(
                        'create_time',
                        'name',
                        'admin_name',
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
		$model=$this->loadModel($id);

		// Проверка прав
		if (!Webadmins::checkAccess('gags_edit', $model->admin_name)) {
            throw new CHttpException(403, "You do not have enough rights");
        }

		// Сохраняем форму
		if(isset($_POST['Gags'])) {

			$model->attributes=$_POST['Gags'];

			if ($model->save()) {
                $this->redirect(array('index'));
			}
        }
		
		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionViewgg()
	{
		$model = Gags::model()->findByPk($_POST['aid']);
		if($model === null)
		{
			Yii::app()->end("alert('Error!')");
		}
		$info = "<table class=\"table table-bordered\">";
		$info .= "<tr>";
		$info .= "<td><b>Player Name</b></td>";
		$info .= "<td>".CHtml::encode($model->name)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Player SteamID</b></td>";
		$info .= "<td>".$model->steamid."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Admin Name</b></td>";
		$info .= "<td>".CHtml::encode($model->admin_name)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Reason</b></td>";
		$info .= "<td>".CHtml::encode($model->reason)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Created</b></td>";
		$info .= "<td>".date("d.m.Y H:i", $model->create_time)."</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Length</b></td>";
		//$data->expired_time==-1? "Expired" : $data->expired_time==0? "Permanent" : Prefs::date2word(($data->expired_time - $data->create_time)/60) . ($data->expired_time<time()? " (Expired)":"") 
		$info .= "<td>". ($model->expired_time==-1? "Expired" : ($model->expired_time==0? "Permanent" : Prefs::date2word(($model->expired_time - $model->create_time)/60) . ($model->expired_time<time()? " (Expired)":"")))  . "</td>";
		//$info .= "<td>". Prefs::date2word(($model->expired_time - $model->create_time)/60) . "</td>";
		$info .= "</tr><tr>";
		$info .= "<td><b>Flags</b></td>";
		$info .= "<td>".$model->GetGagFlags()."</td>";
		$info .= "</tr>";
		$info .= "</table>";
		$js = "$('#gagInfo').html('".$info."');";
		$js .= "$('#loading').hide();";
		$js .= "$('#gagDetails').modal('show');";
		Yii::app()->end($js);
	}

	public function actionCreate()
	{
		if (!Webadmins::checkAccess('gags_add')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Gags;

		$this->performAjaxValidation($model);

		if(isset($_POST['Gags'])) {
			$model->attributes=$_POST['Gags'];
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
		$model=Gags::model()->findByPk($id);
		if ($model === null) {
            throw new CHttpException(404, 'Not found.');
        }
        return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='gags-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
