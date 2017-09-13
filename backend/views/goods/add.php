<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/11
 * Time: 13:58
 */
use yii\web\JsExpression;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'goods_category_id')->hiddenInput();
echo "<ul id=\"treeDemo\" class=\"ztree\"></ul>";
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brands,'id','name'));
echo $form->field($model,'logo')->hiddenInput();

///////////////////////uploadifive插件


//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将文件路径写入隐藏域
        $("#goods-logo").val(data.fileUrl);
        //图片回显
        $("#img").attr("src",data.fileUrl);
       
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo,['id'=>'img','style'=>'width:150px']);
echo $form->field($model,'market_price')->textInput();
echo $form->field($model,'shop_price')->textInput();
echo $form->field($model,'is_on_sale')->radioList([1=>'在售',0=>'下架']);
echo $form->field($model,'status')->radioList([1=>'正常',0=>'回收站']);
echo $form->field($model,'stock')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($intros,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        ]
]);
echo \yii\bootstrap\Html::submitButton('提交');
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
		    $("#goods-goods_category_id").val(treeNode.id);
		}
	}
        };
        // t zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$goodscategorys};
           
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            
            //展开全部节点
           zTreeObj.expandAll(true);
           //获取节点
                var node = zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null);
                console.log(node);
                //选中节点
                zTreeObj.selectNode(node);
       
JS

));