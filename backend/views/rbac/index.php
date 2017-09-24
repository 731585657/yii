<?php
/* @var $this yii\web\View */
?>
<a href="<?=\yii\helpers\Url::to(['rbac/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>权限名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission):?>
    <tr data-id="<?=$permission->name?>">
        <td><?=$permission->name ?></td>
        <td><?=$permission->description ?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['rbac/edit','name'=>$permission->name])?>" class="btn btn-primary">修改</a>
            <a href="javascript:;" class="btn btn-primary del_btn">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>


<?php
//定义连接地址
$del_url=\yii\helpers\Url::to(['rbac/del']);
$js=<<<JS
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