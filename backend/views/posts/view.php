<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\BlogPosts */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blog Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="blog-posts-view">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <?= Html::encode($this->title) ?>
            </h3>
        </div>
        <div class="panel-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    [                      // the owner name of the model
                        'label' => 'Slug',
                        'value' => '/' . $model->slug,
                    ],
                    [
                        'label' => 'Image',
                        'value' => Yii::$app->urlManager->baseUrl . '/img/' . $model->image,
                        'format' => ['image', ['width' => '100', 'height' => '100']],
                    ],
                    [                      // the owner name of the model
                        'label' => 'Category',
                        'value' => $model->category->name,
                    ],
                    [                      // the owner name of the model
                        'label' => 'Status',
                        'value' => $model->status ? 'Active' : 'Inactive',
                    ],
                    'tags',
                    [                      // the owner name of the model
                        'label' => 'Author',
                        'value' => (count($users_list) && array_key_exists($model->author, $users_list) ? $users_list[$model->author] : 'NA'),
                    ],
                    [                      // the owner name of the model
                        'label' => 'Publish Date',
                        'value' => date('m/d/y', strtotime($model->publish_date)),
                    ],
                    [                      // the owner name of the model
                        'label' => 'Description',
                        'value' => $model->description,
                    ],
                ],
            ])

            ?>
        </div>
        <div class="panel-footer text-center">
            <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])

            ?>
        </div>
    </div>
</div>