<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/16
 * Time: 11:23
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permissions',['inline'=>true])->checkboxList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();






