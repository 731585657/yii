<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/15
 * Time: 11:41
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'passwords')->passwordInput();
echo $form->field($model,'repassword')->passwordInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();