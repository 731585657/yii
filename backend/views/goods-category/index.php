<?php
/* @var $this yii\web\View */
?>
<a href="<?= \yii\helpers\Url::to(['goods-category/add']) ?>" class="btn btn-primary">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>分类名称</th>
        <th>简介</th>
        <th></th>
    </tr>
    <?php foreach ($models as $model): //var_dump($model['id']);exit;?>
    <tr>
        <td><?= $model['id'] ?></td>
        <td><?= $model['name']?></td>
        <td><?= str_repeat('——',$model['depth']).$model['name'] ?></td>
        <td><?= $model['intro']?></td>
        <td>
            <a href="<?= \yii\helpers\Url::to(['goods-category/edit','id'=>$model['id']]) ?>" class="btn btn-primary">修改</a>
            <a href="<?= \yii\helpers\Url::to(['goods-category/del','id'=>$model['id']]) ?>" class="btn btn-primary">删除</a>
        </td>
    </tr>
    <?php  endforeach;?>
</table>



