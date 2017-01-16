<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BlogPostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blog Posts';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="blog-posts-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <?= Html::encode($this->title) ?>
                <?php echo Html::a('Add Posts', array('create'), array('class' => 'btn btn-success btn-sm pull-right')); ?>
            </h3>
        </div>
        <div class="panel-body table-responsive">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'title',
                    [                      // the owner name of the model
                        'attribute' => 'category_id',
                        'label' => 'Category',
                        'filter' => ['' => 'Select Category'] + $category_list,
                        'value' => function ($model) {
                        return $model->category->name;
                    },
                    ],
                    [                      // the owner name of the model
                        'attribute' => 'author',
                        'label' => 'Author',
                        'filter' => ['' => 'Select Author'] + $users_list,
                        'value' => function($model) {
                        return $model->getAuthorName($model->author);
                    },
//                        'value' => function ($model, $users_list) {
//                        return (array_key_exists($model->author, $users_list) ? $users_list[$model->author] : 'NA');
//                    },
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Status',
                        'filter' => ['' => 'Select Status', '0' => 'Inactive', '1' => 'Active'],
                        'value' => function ($model) {
                        return $model->status ? 'Active' : 'Inactive';
                    },
                    ],
                    [
                        'attribute' => 'publish_date',
                        'label' => 'Publish Date',
                        'filter' => false,
                        'value' => function ($model) {
                        return $model->publish_date ? date('m/d/y', strtotime($model->publish_date)) : 'NA';
                    },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model) {
                            return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>', ['posts/view', 'id' => $model->id], [
                                    'title' => 'View',
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-sm btn-default'
                                    ]
                            );
                        },
                            'update' => function ($url, $model) {
                            return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>', ['posts/update', 'id' => $model->id], [
                                    'title' => 'Update',
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-sm btn-primary'
                                    ]
                            );
                        },
                            'delete' => function ($url, $model) {
                            return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>', ['posts/delete', 'id' => $model->id], [
                                    'title' => 'Delete',
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-sm btn-danger',
                                    'data-confirm' => 'A re you sure you want to delete this item?',
                                    'data-method' => 'post'
                                    ]
                            );
                        },
                        ],
                    ],
                ],
            ]);

            ?>
        </div>
    </div>   
</div>