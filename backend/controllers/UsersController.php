<?php namespace backend\controllers;

use Yii;
use common\models\User;
use frontend\models\SignupForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\Posts;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Function of default action
     * 
     * @return type
     */
    public function actions()
    {
        $actions = parent::actions();
        // disable the "delete" and "update" actions
        unset($actions['delete'], $actions['update']);
        return $actions;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->password = 'admin1234';
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'User created successfully.');
                return $this->redirect(['index']);
            }
        }
        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if ($id && is_numeric($id)) {
            $model = $this->findModel($id);
            if ($model->status == 10) {
                $model->status = 0;
            } else {
                $model->status = 10;
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'User has been updated successfully');
            } else {
                Yii::$app->session->setFlash('error', 'Error while updating user');
            }
        }
        $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList($id)
    {
        if ($id && is_numeric($id)) {
            $data = Posts::find()->where(['author' => $id])->asArray()->all();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
