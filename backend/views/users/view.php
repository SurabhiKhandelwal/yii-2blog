<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [                      // the owner name of the model
                'label' => 'Status',
                'value' => ($model->status == 10 ? 'Active' : 'Inactive'),
            ],
            [                      // the owner name of the model
                'label' => 'Created At',
                'value' => date('m/d/y', strtotime($model->created_at)),
            ],
            [                      // the owner name of the model
                'label' => 'Updated At',
                'value' => date('m/d/y', strtotime($model->updated_at)),
            ],
        ],
    ])

    ?>

</div>