<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Category;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\BlogPosts */
/* @var $form yii\widgets\ActiveForm */
$categories = ArrayHelper::map(Category::find()->where(['=', 'status', 1])->all(), 'id', 'name');
$users_list = ArrayHelper::map(User::find()->where(['=', 'status', 10])->all(), 'id', 'username');

?>
<div class="blog-posts-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'files' => 'true']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->dropDownList($users_list, ['prompt' => 'Select Author']); ?>

    <?= $form->field($model, 'tags')->textInput(['class' => 'form-control', "data-role" => "tagsinput", 'placeholder' => 'Tags']) ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => 'Select Category']); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'image')->fileInput(['accept' => 'image/*']) ?>
    <?php if ($model->image): ?>
        <div class="form-group">
            <img width="220" height="150" class="img img-responsive img-thumbnail" src="<?= Yii::$app->urlManager->baseUrl . '/img/' . $model->image ?>"/>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'status')->dropDownList(['' => 'Select Status', '1' => 'Published', '0' => 'Unpublished']); ?>

    <?= $form->field($model, 'publish_date')->widget(DatePicker::className())->textInput(['placeholder' => 'Publish Date', 'class' => 'form-control', 'value' => ($model->publish_date ? date('M d,Y', strtotime($model->publish_date)): '')]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>