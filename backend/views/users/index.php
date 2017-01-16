<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users List';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="blog-posts-index">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>
                <?= Html::encode($this->title) ?>
                <?php echo Html::a('Add User', array('create'), array('class' => 'btn btn-success btn-sm pull-right')); ?>
            </h3>
        </div>
        <div class="panel-body table-responsive">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'username',
                    'email:email',
                    [
                        'attribute' => 'status',
                        'label' => 'Status',
                        'filter' => ['0' => 'Inactive', '10' => 'Active'],
                        'value' => function ($model) {
                        return $model->status == 10 ? 'Active' : 'Inactive';
                    },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{change_status} {view_posts}',
                        'buttons' => [
                            'change_status' => function ($url, $model) {
                            return Html::a(
                                    '<span class="">' . ($model->status == 10 ? 'Deactivate' : '&nbsp;&nbsp;&nbsp;Activate&nbsp;&nbsp;&nbsp;') . '</span>', ['users/update', 'id' => $model->id], [
                                    'title' => ($model->status == 10 ? 'Deactivate' : 'Activate'),
                                    'data-pjax' => '0',
                                    'class' => ($model->status == 10 ? 'btn btn-sm btn-danger' : 'btn btn-sm btn-warning')
                                    ]
                            );
                        },
                            'view_posts' => function ($url, $model) {
                            return Html::a(
                                    '<span class="">View Posts</span>', ['posts/index', 'PostsSearch[author]' => $model->id], [
                                    'title' => 'View Posts',
                                    'data-pjax' => '0',
                                    'class' => 'btn btn-sm btn-info'
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