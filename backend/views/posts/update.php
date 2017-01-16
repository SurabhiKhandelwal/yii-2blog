<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BlogPosts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="blog-posts-update">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">

            <?=
            $this->render('_form', [
                'model' => $model,
            ])

            ?>
        </div>
    </div>
</div>