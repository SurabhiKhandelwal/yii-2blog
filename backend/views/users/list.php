<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Category';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                Blogs
               
            </h3>
        </div>
        <div class="table-responsive panel-body">
            <table class="table table-hover">
                <tr>
                    <td>Name</td>
                    <td>Status</td>
                    <td class="text-center">Actions</td>
                </tr>
                <?php
                if (isset($models) && count($models)) {
                    foreach ($models as $category):
                        ?>
                        <tr>
                            <td><?php echo Html::a($category->name, array('category/create', 'id' => $category->id)); ?></td>
                            <td><?php echo $category->status == 1 ? "Active" : "Inactive" ?></td>
                            <td class="text-center">
                                <?php echo Html::a('<span class="glyphicon glyphicon-edit"></span>', array('category/create', 'id' => $category->id), array('class' => 'btn btn-default btn-sm')); ?>
                                <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span>', array('category/delete', 'id' => $category->id), array('class' => 'btn btn-danger btn-sm')); ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                }else {

                    ?>
                    <tr><td colspan="3">No Record Found</td></tr>
                <?php }

                ?>
            </table>
        </div>
        <div class="panel-footer text-center">
            <?php
            echo LinkPager::widget([
                'pagination' => $pagination,
            ]);

            ?>
        </div>
    </div>
</div>
