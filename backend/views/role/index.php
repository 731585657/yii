<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/16
 * Time: 12:10
 */
?>
<a href="<?=\yii\helpers\Url::to(['role/add'])?>" class="btn btn-primary">添加</a>
    <table id="table_id_example" class="display">
        <thead>
            <tr>
                <th>角色名称</th>
                <th>描述</th>
                <th>权限</th>
                <th>操作</th>
            </tr>
        </thead>
    <?php foreach ($models as $model):?>
        <tbody>
            <tr data-id="<?=$model->name?>">
                <td><?=$model->name ?></td>
                <td><?=$model->description ?></td>
                <td><?=$model->description ?></td>

                <td>
                    <a href="<?=\yii\helpers\Url::to(['role/edit','name'=>$model->name])?>" class="btn btn-primary">修改</a>
                    <a href="javascript:;" class="btn btn-primary del_btn">删除</a>
                </td>
            </tr>
        </tbody>
    <?php endforeach; ?>
</table>


<?php
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends'=>\yii\web\JqueryAsset::className()]);
//定义连接地址
$del_url=\yii\helpers\Url::to(['role/del']);
$js=<<<JS
            <!--第三步：初始化Datatables-->
$(document).ready( function () {
    $('#table_id_example').DataTable();
} );
            
        //删除
      $('.del_btn').click(function(){
         if(confirm('确定删除吗')){
             var tr=$(this).closest('tr');
             var name=tr.attr('data-id');
             //console.debug(name);
           $.post('{$del_url}',{name:name},function(data) {
              //console.debug(data);exit;
             if(data == 'success'){
                 alert('删除成功');
                 tr.hide('slow');
             }else {
                 alert('删除失败');
             }
           })
         }
         
      });
        
     
      
JS;
$this->registerJs($js);
