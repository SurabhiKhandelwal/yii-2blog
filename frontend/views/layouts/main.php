<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body id="page-top" data-spy="scroll" data-target=".navbar-custom">
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            $url_params = Yii::$app->urlManager->parseRequest(Yii::$app->request);
            $route = 'site/index';
            $class_new = '';
            if (count($url_params) > 0 && $url_params[0] != $route) {
                $class_new = "background:#f9f9f9 none repeat scroll 0 0";
            }

            NavBar::begin([
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'style' => $class_new,
                    'class' => 'navbar navbar-custom navbar-fixed-top',
                    'role' => "navigation"
                ],
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
                ['label' => 'Blog', 'url' => ['/blog/index']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>';
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();

            ?>
            <?php
            if (count($url_params) > 0 && $url_params[0] == $route) {

                ?>
                <section id="intro" class="intro">
                    <div class="slogan">
                        <h2>WELCOME TO <span class="text_color">SQUAD</span> </h2>
                        <h4>Blog Website</h4>
                    </div>
                    <div class="page-scroll">
                        <p>&nbsp;</p> <p>&nbsp;</p> <p>&nbsp;</p> <p>&nbsp;</p><p>&nbsp;</p>
                    </div>
                </section>
            <?php } else { ?>
                <section id="intro" class="home-section">
                    <hr style="margin-top: 35px;"/>
                </section>
            <?php } ?>
            <section style=" padding-top: 40px;" id="about" class="home-section text-center">

                <!--//                Breadcrumbs::widget([
                //                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                //                ])-->
                <?= $content ?>
            </section>
        </div>
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <p><?= Yii::powered() ?></p>
                        <p>&copy; Surabhi Khandelwal <?= date('Y') ?>.</p>
                    </div>
                </div>	
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
