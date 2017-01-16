<?php namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * Category controller
 */
class BlogController extends ActiveController
{

    public $modelClass = 'backend\models\Posts';

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

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_bck()
    {
        $model = new $this->modelClass;
        $model->scenario = "create";
        $model->load(Yii::$app->request->post(), '');
        if (!$model->validate()) {
            return $model;
        } else {
            $post_data = Yii::$app->request->post();
            $model->slug = $this->createSlug($post_data['title']);
            $model->publish_date = Yii::$app->formatter->asDate($post_data['publish_date'], "Y-M-d");
            $image_name = $this->uploadImage();
            if (isset($image_name) && ($image_name != 0 || !empty($image_name))) {
                $model->image = $image_name;
            }
            echo '<pre>';
            print_r($model);
            exit;
        }

        if ($model->load(Yii::$app->request->post(), '')) {


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
            return ['success' => '1'];
        }

        $model->validate();
        return $model;
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate_bck($id)
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
    public function actionDelete_bck($id)
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
    protected function uploadImage_bck()
    {
        $model = new $this->modelClass;

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
    protected function createSlug_bck($title)
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
