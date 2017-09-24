
<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/13
 * Time: 17:49
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model, 'rememberMe')->checkbox();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();