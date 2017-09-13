<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/10
 * Time: 12:51
 */
use \kucha\ueditor\UEditor;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->hiddenInput();
echo "<ul id=\"treeDemo\" class=\"ztree\"></ul>";

echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();



//注册js
/**
 * @var $this \yii\web\View
 */
//注册css
$this->registerCssFile('@web/ztree/css/zTreeStyle/zTreeStyle.css');
//注册js
$this->registerJsFile('@web/ztree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//接收模型返回的数据
$goodscategorys=json_encode(\backend\models\GoodsCategory::getZNodes());
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		onClick:function(event, treeId, treeNode){
		    console.log(treeNode);
		    //将打印得到的值写入节点中
		    $("#goodscategory-parent_id").val(treeNode.id);
		}
	}
        };
        // t zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$goodscategorys};
           
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            
            //展开全部节点
           zTreeObj.expandAll(true);
           //获取节点
                var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);
                console.log(node);
                //选中节点
                zTreeObj.selectNode(node);
       
JS

));

