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
    <?php foreach ($models as $model): //var_dump($models);exit; ?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article-category/edit','id'=>$model->id]) ?>" class="btn btn-primary">修改</a>
                <a href="<?=\yii\helpers\Url::to(['article-category/del','id'=>$model->id]) ?>" class="btn btn-primary">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$requy,
]);

