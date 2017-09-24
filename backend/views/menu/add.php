<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/17
 * Time: 14:13
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($rows,'id','name'),['prompt'=>'请选择分类']);
echo $form->field($model,'route')->dropDownList(\yii\helpers\ArrayHelper::map($permissions,'name','name'),['prompt'=>'请选择路由']);
echo $form->field($model,'soft')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();