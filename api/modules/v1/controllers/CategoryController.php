<?php namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Category controller
 */
class CategoryController extends ActiveController
{

    public $modelClass = 'api\modules\v1\models\Category';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

//        $behaviors['corsFilter'] = [
//            'class' => \yii\filters\Cors::className(),
//            'cors' => [
//                'Origin' => ['*'],
//                'Access-Control-Request-Method' => ['GET'],
//            ],
//        ];
        
        return $behaviors;
    }
}
