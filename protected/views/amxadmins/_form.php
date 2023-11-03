<?php
/**
 * @var Bans $model
 * Форма добавления/редактирования AmxModX админа
 */

/**
 * @author Craft-Soft Team
 * @package CS:Bans
 * @version 1.0 beta
 * @copyright (C)2013 Craft-Soft.ru.  Все права защищены.
 * @link http://craft-soft.ru/
 * @license http://creativecommons.org/licenses/by-nc-sa/4.0/deed.ru  «Attribution-NonCommercial-ShareAlike»
 */

Yii::app()->clientScript->registerScript('adminactions', '
	var days = $("#Amxadmins_expires");
	var flags = $("#Amxadmins_flags");
	var password = $("#Amxadmins_password");
	var forever = $("#forever");
	var placeholder;
	

	forever.click(function() {
		if($(this).prop("checked")) {
			days.val("0");
			days.attr("readonly", "readonly");
		} else {
			days.removeAttr("readonly");
			days.val("30");
		}
	});

	flags.change(function(){
        $("#removePwd").removeAttr("disabled");
		switch($(this).val()) {
			case "d":
				placeholder = "127.0.0.1";
				break;
			case "c":
				placeholder = "STEAM_0:0:00000000";
				break;
			case "a":
				placeholder = "";
				$("#removePwd").attr("disabled", true);
				break;
		}
		$("#Amxadmins_steamid").attr("placeholder", placeholder);
	});

    $("#removePwd").click(function(){
        if($(this).attr("checked")) {
            $("#Amxadmins_password").attr("disabled", true);
        } else {
            $("#Amxadmins_password").removeAttr("disabled");
        }
    });
');

if(!$model->isNewRecord) {
	if( $model->flags[0] != 'c' && $model->flags !='ca')
		$model->flags = $model->flags[0];
	
}

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'amxadmins-form',
	'enableAjaxValidation'=>false,
));

	echo $form->errorSummary($model);
	if ($model->isNewRecord) {
        echo $form->errorSummary($webadmins);
    }

    echo $form->dropDownListRow(
		$model,
		'flags',
		Amxadmins::getAuthType(),
		array(
			'class' => 'span6',
			//'maxlength'=>32,
		)
	);
	
	echo $form->textFieldRow($model,'username',array('class' => 'span6','maxlength'=>32));
	echo $form->textFieldRow($model,'steamid',array('class' => 'span6','maxlength'=>32));
	echo $form->textFieldRow($model,'email',array('class' => 'span6','maxlength'=>32));
    
    if(!$model->isNewRecord && $model->flags != 'a' && $model->password) {
        $htmlOptions = array(
            'append' => '<label>'.CHtml::checkBox('removePwd') . 'Delete Password</label>',
            'style' => 'width: 167px',
            'value' => isset($_POST['Amxadmins']['password']) ? CHtml::encode($_POST['Amxadmins']['password']) : '',
        );
    } else {
        $htmlOptions = array(
            'class' => 'span6',
            'value' => isset($_POST['Amxadmins']['password']) ? CHtml::encode($_POST['Amxadmins']['password']) : '',
        );
    }
    
	echo $form->passwordFieldRow(
		$model,
		'password',
		$htmlOptions
	);
	
	echo $form->dropDownListRow(
		$model,
		'access',
		Amxadmins::GetMyFlags( 'z', TRUE ),
		array(
			'class' => 'span6'
		)
	);
	
	//echo $form->dropDownListRow($model,'ashow', array('No', 'Yes'),array('class' => 'span6'));
	
	if($model->isNewRecord):
		echo $form->textFieldRow(
			$model,
			'expires',
			array(
				'class' => 'span6',
				'value' => '0',
				'readonly' => 'readonly',
				'append' => '<label>'.CHtml::checkBox('', true, array('id' => 'forever')) . ' Permanent</label>'
			)
		);
	else:
	?>
	<label for="Amxadmins_change">Change admin timeline</label>
	<div class="row-fluid">
		<div class="span2">

			<label class="radio"><input id="Amxadmins_addtake_0" value="0" type="radio" name="Amxadmins[addtake]" checked /> Add</label>

			<label class="radio"><input id="Amxadmins_addtake_1" value="1" type="radio" name="Amxadmins[addtake]" <?php echo ($model->expires == 0 || $model->expires < time())? ' disabled':'' ?>/> Remove</label>

			<label class="radio"><input id="Amxadmins_addtake_2" value="2" type="radio" name="Amxadmins[addtake]"<?php echo $model->expires == 0 ? ' checked="checked"' : ''?> /> Permanent</label>
		</div>
		<div class="offset2 span2">
			<div class="input-append pull-right" style="padding-top: 5px">
				<input class="input-small" name="Amxadmins[change]" id="Amxadmins_change" type="text" />
				<span class="add-on">Days</span>
			</div>
		</div>
	</div>

	<?php
	endif;
	if($model->isNewRecord):?>
		<hr class="row-divider">
		<button class="btn btn-info" type="button" onclick="$('#webrights').slideToggle('slow');">Add Web Admin</button>
		<div id="webrights" style="display: none"><br>
			<?php echo $form->textFieldRow($webadmins,'username',array('class' => 'span6','size'=>32,'maxlength'=>32, 'value' => 'AMX admin nickname will be used', 'disabled' => 'disabled'));?>
			<?php echo $form->passwordFieldRow($webadmins,'password',array('class' => 'span6','size'=>32,'maxlength'=>32, 'value' => '')); ?>
			<?php echo $form->textFieldRow($webadmins,'email',array('class' => 'span6','size'=>60,'maxlength'=>64)); ?>
			<?php echo $form->dropdownListRow($webadmins,'level', Levels::getList(), array('class' => 'span6')); ?>
		</div>
	<?php endif;?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Add' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget();?>