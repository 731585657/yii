<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/9/8
 * Time: 22:03
 */
?>
<a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-primary">返回列表</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>文章内容</th>
    </tr>

        <tr>
            <td><?=$model->article_id ?></td>
            <td><?=$model->content ?></td>
        </tr>

</table>