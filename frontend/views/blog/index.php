<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
use yii\widgets\LinkPager;
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
                    if (count($blogs)) {
                        foreach ($blogs as $blog) {
                            $count_word_desc = str_word_count($blog->description);

                            ?>
                            <div class="boxed-grey clearfix">
                                <div class="col-md-6">
                                    <br>
                                    <div class="tch-img">
                                        <a href=""><img src="<?= Yii::$app->urlManagerBackend->baseUrl . '/img/' . $blog->image ?>" class="img-responsive" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <br/>
                                    <h3><?= $blog->title; ?></h3>
                                    <p><?= $count_word_desc <= 50 ? $blog->description : substr($blog->description, 0, 250) . '....' ?></p>
                                    <p>
                                        <a style="margin-right:5px;" class="btn btn-danger btn-sm" href="<?= Url::toRoute(['blog/view', 'id' => $blog->id]); ?>">Read More</a>
                                        <a style="margin-right:5px;" class="btn btn-info btn-sm" href="<?= Url::toRoute(['blog/view', 'id' => $blog->id]) . '#comments_head'; ?>">Comments (<?= isset($blog->id) && array_key_exists($blog->id, $comment_count_array) ? $comment_count_array[$blog->id] : 0 ?>)</a>
                                    </p>
                                </div>
                            </div>
                            <hr/>
                            <?php
                        }
                    } else {

                        ?>
                        <div class="boxed-grey clearfix">
                            <div class="col-sm-12">No Record Found</div>
                        </div>

                        <?php
                    }

                    if (isset($pagination) && !empty($pagination)) {

                        ?>
                        <div class="clearfix">
                            <?php
                            echo LinkPager::widget([
                                'pagination' => $pagination,
                            ]);

                            ?>
                        </div> 

                    <?php }

                    ?>
                </div>
                <div class="col-sm-3 col-md-3">
                    <?=
                    $this->render('category_list', [
                        'categories' => $categories,
                        'data_url' => $data_url
                    ])

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
