<?php namespace backend\controllers;

use Yii;
use backend\models\Posts;
use backend\models\PostsSearch;
use backend\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * PostsController implements the CRUD actions for Posts model.
 */
class PostsController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Posts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'users_list' => $this->getAuthorList(),
                'category_list' => $this->getCategoryList(),
        ]);
    }

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
                'users_list' => $this->getAuthorList()
        ]);
    }

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Posts;

        $post_data = Yii::$app->request->post();

        if (Yii::$app->request->isPost) {

            $model->slug = $this->createSlug($post_data['Posts']['title']);
            $image_name = $this->uploadImage();

            if (isset($image_name) && ($image_name != 0 || !empty($image_name))) {
                $post_data['Posts']['image'] = $image_name;
                $post_data['Posts']['publish_date'] = Yii::$app->formatter->asDate($post_data['Posts']['publish_date'], "Y-M-d");

                $model->load($post_data);

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Your post created successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Error');
                    return $this->render('create', ['model' => $model]);
                }
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = "update";

        $oldFileName = $model->getOldAttribute('image');
        $oldFile = $model->getImageFile($oldFileName);

        $post_data = Yii::$app->request->post();

        if (Yii::$app->request->isPost) {

            $model->slug = $this->createSlug($post_data['Posts']['title']);

            $image_name = $this->uploadImage();

            if ($image_name === false) {
                $post_data['Posts']['image'] = $oldFileName;
            } else if (isset($image_name) && ($image_name != 0 || !empty($image_name))) {
                $post_data['Posts']['image'] = $image_name;
                $model->deleteImage($oldFileName);
            }
            $post_data['Posts']['publish_date'] = Yii::$app->formatter->asDate($post_data['Posts']['publish_date'], "Y-M-d");

            $model->load($post_data);

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Your post updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Error');
                return $this->render('update', ['model' => $model]);
            }
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        // get post image name
        $image_Data = $model->image;
        if (!empty($image_Data)) {
            // unlink image from folder
            $model->deleteImage($image_Data);
        }
        // delete post and set message
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', 'Post has been removed successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Some error occured');
        }
        // redirect
        return $this->redirect(['index']);
    }

    /**
     * Function to get author list
     * 
     * @param none
     * @return mixed
     */
    protected function getAuthorList()
    {
        $users_list = ArrayHelper::map(User::find()->where(['=', 'status', 10])->all(), 'id', 'username');
        return $users_list;
    }

    /**
     * Function to get category list
     * 
     * @param none
     * @return mixed
     */
    protected function getCategoryList()
    {
        $category_list = ArrayHelper::map(Category::find()->where(['=', 'status', 1])->all(), 'id', 'name');
        return $category_list;
    }

    /**
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Method to upload image
     * 
     * @param none
     * @return string|boolean
     */
    protected function uploadImage()
    {
        $model = new Posts();

        if (Yii::$app->request->isPost) {
            $imageFile = UploadedFile::getInstance($model, 'image');
            // if no image was uploaded abort the upload
            if (empty($imageFile)) {
                return false;
            }
            // creation of file name
            $fileName = uniqid('blog_') . $imageFile->baseName . '.' . $imageFile->extension;
            // move file
            $image_up = $imageFile->saveAs('img/' . $fileName);

            if ($image_up) {
                return $fileName;
            }
        }
        return false;
    }

    /**
     * Function to create a slug for a given title
     * 
     * @param type String
     * @return type String
     */
    protected function createSlug($title)
    {
        // Convert title to lowercase and Replace blank spaces with hiphens
        $slug = str_replace(' ', '-', strtolower($title));
        if (strpos($slug, '?') !== false) {
            //PERCENT SIGN FOUND
            $slug = str_replace('?', '', $slug);
        }
        $i = 0;
        $updatedSlug = $slug;
        //While the created slug is already present in the database

        if (Posts::find()->where(['=', 'slug', $updatedSlug])->exists()) {
            //Increment the index
            $i = $i + 1;
            //Append the index to the slug
            $updatedSlug = $slug . '-' . $i;
        }
        return $updatedSlug;
    }
}
