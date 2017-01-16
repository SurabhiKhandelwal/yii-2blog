<?php namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use backend\models\Category;
use yii\data\Pagination;
use backend\models\Posts;

/**
 * Category controller
 */
class CategoryController extends Controller
{

    /**
     * Method to check error and call action
     * @return type
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Method to find category id
     * @param type $id
     * @return type
     * @throws HttpException
     */
    private function findModel($id)
    {
        // find the category by id
        $model = Category::findOne($id);

        if ($model == NULL) {
            // throw exception if id not exist
            throw new HttpException(404, 'Model not found.');
        }

        return $model;
    }

    /**
     * Displays category listing.
     *
     * @return view
     */
    public function actionIndex()
    {
        // build a DB query to get all category
        $query = Category::find();

        // get the total number of category
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 10]);

        // limit the query using the pagination and retrieve the category
        $data = $query->offset($pagination->offset)->limit(10)->all();

        return $this->render('index', ['models' => $data, 'pagination' => $pagination]);
    }

    /**
     * Method to delete category
     * @param int $id
     */
    public function actionDelete($id = NULL)
    {
        // get the catgory data
        $model = $this->findModel($id);
        // check its existence
        if (Posts::find()->where(['category_id' => $id])->exists()) {
            Yii::$app->session->setFlash('error', 'Please delete posts related to this category first.');
        } else if (!$model->delete()) {
            Yii::$app->session->setFlash('error', 'Unable to delete category');
        } else {
            Yii::$app->session->setFlash('success', 'Category has been removed successfully.');
        }
        // redirect
        $this->redirect(['index']);
    }

    /**
     * Method to add/update the category
     * @param type $id
     * @return view
     */
    public function actionCreate($id = NULL)
    {
        if ($id == NULL) {
            $model = new Category;
        } else {
            $model = $this->findModel($id);
        }

        // if post then save
        if (isset($_POST['Category'])) {
            $model->load(Yii::$app->request->post());

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Category has been saved');
                $this->redirect(['create', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error while submitting category');
            }
        }
        // display form
        return $this->render('create', ['model' => $model]);
    }
}
