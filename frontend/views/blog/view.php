<?php
/* @var $this yii\web\View */

$this->title = 'My Blog Application';
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="blog-index">
    <div class="heading-contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <div class="wow bounceInDown" data-wow-delay="0.4s">
                        <div class="section-heading">
                            <h2>Blogs</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content container">
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <hr class="marginbot-50">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-9 col-md-9">
                    <?php
                    if (isset($blog) && !empty($blog) && count($blog) > 0) {
                        $tags = explode(",", $blog[0]->tags);

                        ?>
                        <div class="boxed-grey clearfix">
                            <div class="marginbot-20"><a href=""><img src="<?= Yii::$app->urlManagerBackend->baseUrl . '/img/' . $blog[0]->image ?>" class="img-responsive" alt=""></a></div>
                            <p><?= $blog[0]->title ?></p>
                            <p>BY <i class="name_auth"><?= array_key_exists($blog[0]->author, $author_list) ? $author_list[$blog[0]->author] : '' ?></i> <?= date('M d Y', strtotime($blog[0]->publish_date)) ?>.</p>
                            <p ><?= $blog[0]->description ?></p>
                            <?php if (count($tags) > 0) { ?>
                                <div class="tagcloud">
                                    <p>Tags :</p>
                                    <?php foreach ($tags as $tag) { ?>
                                        <a><?= $tag ?></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <hr id="comments_head" class="marginbot-20">
                            <?php if (count($comments_data)) { ?>
                                <h4>Comments</h4>
                                <?php foreach ($comments_data as $comment) { ?>
                                    <div class="panel panel-default panel-body">
                                        <h5 style="margin-bottom: 12px;color:#67b0d1;"><?php echo $comment['name'] ?></h5>
                                        <p class="marginbot-10" style="">
                                            <?php echo '- ' . $comment['message'] ?>
                                        </p>
                                    </div>
                                <?php } ?>
                                <hr class="marginbot-20">
                            <?php } ?>
                            <h4>Leave your comment</h4>
                            <?php $form = ActiveForm::begin(['id' => 'comment-form', 'action' => ['comment'],]); ?>
                            <input type="hidden" name="blog_slug" value="<?= $blog[0]->slug ?>"/>
                            <?= $form->field($model, 'blog_id')->hiddenInput(['value' => $blog[0]->id])->label(false) ?>                                 
                            <div class="form-group">
                                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'email')->textInput() ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'message')->textarea(['rows' => 2]) ?>
                            </div>
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-sm btn-info', 'name' => 'comment-button']) ?>

                            <?php ActiveForm::end(); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-3 col-md-3">

                    <?=
                    $this->render('related-post', [
                        'related_posts' => $related_posts,
                        'blog' => $blog
                    ]);

                    ?>
                    <?=
                    $this->render('category_list', [
                        'categories' => $categories,
                        'blog' => $blog
                    ]);

                    ?>
                </div>
            </div>                           
        </div>
        <div class="row">
            <div class="col-lg-2 col-lg-offset-5">
                <br class="marginbot-50">
            </div>
        </div>
    </div>
</div>
