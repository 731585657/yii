
<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/13
 * Time: 16:18
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'status')->radioList([10=>'启用',0=>'未启用']);
echo $form->field($model,'email')->textInput();
echo $form->field($model,'permissions')->checkboxList(\backend\models\Admin::getPermissionItems());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();