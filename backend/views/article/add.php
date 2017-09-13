<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/8
 * Time: 16:20
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map($article,'id','name'));
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([1=>'下架',2=>'上架']);
echo $form->field($articled,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>"btn btn-info"]);

\yii\bootstrap\ActiveForm::end();