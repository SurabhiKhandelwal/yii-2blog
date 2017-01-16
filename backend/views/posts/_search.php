<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BlogPostsSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="blog-posts-search">

    <?php
    $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
    ]);

    ?>

    <?php $form->field($model, 'id') ?>

    <?php $form->field($model, 'title') ?>

    <?php echo $form->field($model, 'category_id') ?>

    <?php echo $form->field($model, 'status') ?>

    <?php echo $form->field($model, 'publish_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>