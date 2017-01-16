<?php
use yii\helpers\Url;

?>
<div class="">
    <?php
    if (count($categories)) {

        ?>
        <div class="panel panel-default category">
            <p class="panel-heading cat_head">
                Categories
                <?php if (isset($data_url) && isset($data_url['category']) && !empty($data_url['category'])) { ?>
                    <a class="label label-info pull-right" href="<?= Url::toRoute(['blog/index']); ?>">Clear All</a>
                <?php } ?>
            </p>
            <div class="panel-body">
                <?php foreach ($categories as $key => $value) { ?>
                    <a href="<?= Url::toRoute(['blog/index', 'category' => $key]); ?>" class=" <?php echo (isset($data_url) && isset($data_url['category']) && $data_url['category'] == $key ? 'text-danger active' : '' ) ?>"><?= $value ?></a>
                    <br class="marginbot-10">
                <?php } ?>
            </div>
        </div>
        <?php
    }

    ?>
</div>