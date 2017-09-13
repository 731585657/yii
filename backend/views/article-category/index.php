<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/7
 * Time: 19:09
 */
?>
<a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr data-id="<?=$model->id?>">
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$model->id]) ?>" class="btn btn-primary">修改</a>
                <a href="javascript:;" class="btn btn-primary del_btn">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
//定义连接地址
$del_url=\yii\helpers\Url::to(['article-category/del']);
$js=<<<JS
      $('.del_btn').click(function(){
         if(confirm('确定删除吗')){
             var tr=$(this).closest('tr');
             var id=tr.attr('data-id');
             //console.debug(id);
           $.post('{$del_url}',{id:id},function(data) {
              //console.debug(data);
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


echo \yii\widgets\LinkPager::widget([
    'pagination'=>$requy,
]);

