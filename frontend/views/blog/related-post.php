<?php
use yii\helpers\Url;

?>
<div class="">
    <?php
    if (count($related_posts)) {
        $check_count = count($related_posts);
        $i = 0;

        ?>
        <div class="panel panel-default">
            <p class="panel-heading cat_head">Related Posts</p>
            <div class="panel-body">
                <?php foreach ($related_posts as $related_post) { ?>
                    <div class="row">
                        <div class="col-sm-7">
                            <img src="<?= Yii::$app->urlManagerBackend->baseUrl . '/img/' . $related_post['image'] ?>" class="img-responsive img-thumbnail" alt="">
                        </div>
                        <div class="col-sm-5">
                            <a href="<?= Url::toRoute(['blog/view', 'id' => $related_post['id']]); ?>" class="text-link"><?= $related_post['title'] ?></a>
                        </div>
                    </div>
                    <?php
                    $i++;
                    if ($i != $check_count) {

                        ?>
                        <hr class="marginbot-20">
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php
    }

    ?>
</div>