<?php namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\NotFoundHttpException;

/**
 * Comments controller
 */
class CommentsController extends ActiveController
{
    
    // base class of model
    public $modelClass = 'backend\models\Comments';

    /**
     * Function for defining behavious of model
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * Function for default actions
     * @return type
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['index']);
        return $actions;
    }

    /**
     * Funtion to get comments on basis of blog id
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $model = new $this->modelClass;
        $data_id = Yii::$app->request->get('blog_id');

        if (isset($data_id) && is_numeric($data_id) && $model::find()->where(['=', 'blog_id', $data_id])->exists()) {
            $data = $model::find()->where(['=', 'blog_id', $data_id])->all();
            return $data;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
