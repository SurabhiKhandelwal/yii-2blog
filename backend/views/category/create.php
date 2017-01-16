<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Add-Edit Category';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">

    <div class="panel panel-default">
        <div class="panel-heading">
            Category
        </div>
        <?php
        $form = ActiveForm::begin(array(
                'options' => array('class' => '', 'role' => 'form'),
        ));

        ?>
        <div class = "panel-body">
            <div class = "col-sm-12">
                <div class="form-group">
                    <?php echo $form->field($model, 'name')->textInput(array('class' => 'form-control')); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->field($model, 'status')->dropDownList(['' => 'Select Status', '1' => 'Active', '0' => 'Inactive']); ?>
                </div>

            </div>
        </div>
        <div class="panel-footer text-center clearfix">
            <?php echo Html::submitButton('Submit', array('class' => 'btn btn-primary pull-right')); ?>
            <?php echo Html::a('Back to Listing', array('category/index'), array('class' => 'btn btn-default pull-left')); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>