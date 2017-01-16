<?php namespace console\controllers;

use yii\console\Controller;

class TestController extends Controller
{

    // run command  - yii test(test is controller name)
    
    public function actionIndex()
    {
        echo "cron service runnning";
    }

    public function actionMail($to)
    {
        echo "Sending mail to " . $to;
    }
}
