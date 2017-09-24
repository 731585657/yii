<?php
/* @var $this yii\web\View */
?>
<h1>admin/index</h1>
<a href="<?=\yii\helpers\Url::to(['admin/add'])?>" class="btn btn-primary">添加</a>
<a href="<?=\yii\helpers\Url::to(['admin/password'])?>" class="btn btn-primary">修改密码</a>
<a href="<?=\yii\helpers\Url::to(['admin/logout'])?>" class="btn btn-primary">退出</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->username?></td>
        <td><?=$model->email?></td>
        <td><?=$model->status==10?'启用':'未启用'?></td>
        <td><?=date('Y-m-d H:i:s',$model->last_login_time)?></td>
        <td><?=$model->last_login_ip?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['admin/edit','id'=>$model->id])?>" class="btn btn-primary">修改</a>
            <a href="<?=\yii\helpers\Url::to(['admin/del','id'=>$model->id])?>" class="btn btn-primary">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>