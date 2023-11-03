<?php

class PromocodeController extends Controller
{
	public $layout='//layouts/column1';
	public function filters()
	{
		return array(
			'accessControl',
			'ajaxOnly + delete'
		);
	}

	public function actionDelete($id)
	{
		$model = $this->loadModel($id);

		if (!Webadmins::checkAccess('code_edit')) {
            throw new CHttpException(403, "You don't have enough rights");
        }

        $model->delete();

		Yii::app()->end('Code Deleted');
    }

	public function actionIndex()
	{
        if (!Webadmins::checkAccess('code_view', Yii::app()->user->name)) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		$dataProvider=new CActiveDataProvider('Promocode', array(
            'criteria'=>array(
                'condition'=>Webadmins::checkAccess('code_view')? '1=1':'codename=\''.Yii::app()->user->name.'\'',
                'order'=>'expires ASC'
            ),
			'pagination' => array(
				'pageSize' =>  Yii::app()->config->bans_per_page),
                'sort' => array(
                    'defaultOrder' => '`id` ASC',
                    'attributes' => array(
                        'codename',
                        'type',
                        'expires'
                    )
                )
            )
		 );
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider
		));

	}


	public function actionCreate()
	{
		if (!Webadmins::checkAccess('servers_edit')) {
            throw new CHttpException(403, "You do not have enough rights");
        }
		
        $model=new Promocode;

		$this->performAjaxValidation($model);

		if(isset($_POST['Promocode'])) {
			$model->attributes=$_POST['Promocode'];
			if ($model->save()) {
                $this->redirect(array('index'));
            }
        }

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        if (!Webadmins::checkAccess('code_view', $model->codename) ) {
            throw new CHttpException(403, "You do not have enough rights");
        }
        $this->render('view',array(
			'model'=>$model,
		));

    }
	public function actionReceive()
	{
		if( !isset($_GET['tx']) )
			throw new CHttpException(404, 'Not found.');
		$pp_hostname = "www.paypal.com";
		$req = 'cmd=_notify-synch';
		$tx_token = $_GET['tx'];
		
		$auth_token = $this->PaypalGetAuthToken();
		$req .= "&tx=$tx_token&at=$auth_token";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://$pp_hostname/cgi-bin/webscr");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		//set cacert.pem verisign certificate path in curl using 'CURLOPT_CAINFO' field here,
		//if your server does not bundled with default verisign certificates.
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $pp_hostname"));
		$res = curl_exec($ch);
		curl_close($ch);
		if(!$res)
			throw new CHttpException(500, 'Error with HTTP request. Contact support@csamx.net.');

		$lines = explode("\n", trim($res));
		$keyarray = array();
		if (strcmp ($lines[0], "SUCCESS") == 0) {
			for ($i = 1; $i < count($lines); $i++) {
				$temp = explode("=", $lines[$i],2);
				$keyarray[urldecode($temp[0])] = urldecode($temp[1]);
			}
			// + check the payment_status is Completed 
			// + check that txn_id has not been previously processed
			// + check that receiver_email is your Primary PayPal email
			// + check that payment_amount/payment_currency are correct
			$info = array();
			if( !$this->PaypalCheckEmail( $keyarray['receiver_email'] ) )
				throw new CHttpException(500, 'Wrong email payment. Contact support@csamx.net.');

			if( $keyarray['payment_status'] != 'Completed' && $keyarray['payment_status'] != 'Pending' )
				throw new CHttpException(401, 'Payment status not completed. If you think it\'s an error, contact support@csamx.net.');

			$tnx_id = $keyarray['txn_id'];  // check if model doesn't have any entry with this one
			
			$model = Promocode::model()->findByAttributes(array('codename'=>$tnx_id));
			if( $model === null )	// means, the code hasn't been created yet
			{
				$type = Promocode::PaypalGetType( $keyarray['mc_currency'], $keyarray['mc_gross'], $keyarray['option_name1'] );
				if( $type == -1 )
					throw new CHttpException(500, 'Error with payment check. Contact support@csamx.net.');
				$model = new Promocode;
				$model->type = $type;
				$model->codename = $tnx_id; 
				$model->save();
			}
			$info['firstname']= $keyarray['first_name'];
			$info['lastname'] = $keyarray['last_name'];
			$info['itemname'] = $keyarray['item_name'];
			$info['email'] = $keyarray['payer_email'];
			//var_dump( $keyarray );
		}
		else if (strcmp ($lines[0], "FAIL") == 0) {
			throw new CHttpException(500, 'Error with request. Contact support@csamx.net.');
		}


		$this->render('receive',array(
			'model'=>$model,
			'info'=>$info
		));
	}

	public function loadModel($id)
	{
		$model=Promocode::model()->findByPk($id);
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

	protected function PaypalCheckEmail( $email )
	{
		if( $email == 'support@csamx.net' )
		//if( $email == 'sb-lh2b54586203@personal.example.com' )
			return true;
		return false;
	}
	protected function PaypalGetAuthToken()
	{
		return "X8s6FinHqzxxWXRIYELbCtGM60nyyCI5b_hXBRJsYMQxSPzLH6_bBR2_R48";
		//return "W2KDnc6ARCRy0MfYqN25VicSymHhqSyR-LTD_BFcqKQAabfTUypWSl95u1q";
	}
}