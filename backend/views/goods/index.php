<?php
/* @var $this yii\web\View */
?>
<h1>goods/index</h1>

<a href="<?=\yii\helpers\Url::to(['goods/add'])?>" class="btn btn-primary">添加</a>
    <form action="<?= \yii\helpers\Url::to(['goods/index'])?>" method="get">
        <input type="text" id="goods-name" name="name" placeholder="商品名">
        <input type="text" id="goods-sn" name="sn" placeholder="货号">
        <input type="text" id="goods-minprice" name="minprice" placeholder="最小金额">
        <input type="text" id="goods-maxprice" name="maxprice" placeholder="最大金额">
        <input type="submit" value="搜索">
    </form>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO</th>
        <th>商品分类</th>
        <th>品牌</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>添加时间</th>

        <th>操作</th>
    </tr>
    <?php foreach ($models as $model ):  ?>
    <tr>
        <td><?=$model->id ?></td>
        <td><?=$model->name ?></td>
        <td><?=$model->sn ?></td>
        <td><img src="<?=$model->logo ?>" class="img-circle" height="100" width="100"></td>
        <td><?=$model->goodsCategory->name?></td>
        <td><?=$model->brand->name?></td>
        <td><?=$model->market_price?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=$model->is_on_sale==1?'在售':'下架' ?></td>
        <td><?=$model->status==1?'正常':'回收站' ?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td><?=$model->view_times?></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['goods/gallery','id'=>$model->id])?>" class="btn btn-primary">相册</a>
            <a href="<?=\yii\helpers\Url::to(['goods/edit','id'=>$model->id])?>" class="btn btn-primary">修改</a>
            <a href="<?=\yii\helpers\Url::to(['goods/del','id'=>$model->id])?>" class="btn btn-primary">删除</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$pager,


]);
