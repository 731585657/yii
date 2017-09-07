<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 19:22
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([1=>'隐藏',2=>'正常']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();