<?php namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\models\Category;
use yii\helpers\ArrayHelper;
use backend\models\Comments;
use common\models\User;

/**
 * Site controller
 */
class BlogController extends Controller
{

    protected $modelClass = 'backend\models\Posts';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays blogs.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // get query parameter
        $data_url = Yii::$app->request->queryParams;
        $model = $this->modelClass;
        $query = $model::find()->where(['=', 'status', 1]);
        // check and apply filters
        if ($data_url && isset($data_url['category'])) {
            $query->andFilterWhere(['=', 'category_id', $data_url['category']]);
        }
        // set pagination
        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);
        // get data from database
        $blogs = $query->orderBy('id')->offset($pagination->offset)->limit(5)->all();
        // find category list
        $categories = $this->findCategory();
        // define array and count comments for every blog
        $comment_count_array = array();
        if (count($blogs)) {
            $comment_count_array = $this->findCommentCount();
        }
        // render view
        return $this->render('index', ['blogs' => $blogs, 'pagination' => $pagination, 'categories' => $categories, 'data_url' => $data_url, 'comment_count_array' => $comment_count_array]);
    }

    /**
     * Function to get comment count
     * @return array
     */
    protected function findCommentCount()
    {
        $query = Comments::find()->select(['blog_id', 'count( id ) AS count'])->groupBy(['blog_id'])->asArray()->all();
        $comment_count_array = ArrayHelper::map($query, 'blog_id', 'count');
        return $comment_count_array;
    }

    /**
     * Function to find category
     * @return array
     */
    protected function findCategory()
    {
        $category_list = ArrayHelper::map(Category::find()->where(['=', 'status', 1])->all(), 'id', 'name');
        return $category_list;
    }

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // get category list
        $categories = $this->findCategory();
        // define model
        $model = $this->modelClass;
        $data = $model::find()->where(['=', 'id', $id])->all();
        $related_post = array();
        // check blog category and find related blog on the basis of category
        if (count($data) && isset($data[0]->category_id)) {
            $category_id = $data[0]->category_id;
            $related_post = $this->getRelatedBlog($category_id, $id);
        }
        // define comments model
        $model_comm = new Comments();
        // get comment data
        $comments_data = $this->getBlogComments($id);
        // get author list
        $author_list = $this->getAuthorList();
        if ($data !== null) {
            // return view
            return $this->render('view', [
                    'blog' => $data,
                    'categories' => $categories,
                    'model' => $model_comm,
                    'comments_data' => $comments_data,
                    'related_posts' => $related_post,
                    'author_list' => $author_list
            ]);
        } else {
            // exception throw if data is not present
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Function to find blog comments on the basis of blog id
     * @return array
     */
    protected function getBlogComments($id)
    {
        $data = Comments::find()->where(['=', 'blog_id', $id])->orderBy('id DESC')->asArray()->all();
        return $data;
    }

    /**
     * Function to find related blog
     * 
     * @param category_id
     * @param blog_id
     * @return array
     */
    protected function getRelatedBlog($category_id, $blog_id)
    {
        $model = $this->modelClass;
        $data = $model::find()->select(['id', 'image', 'title'])->where(['=', 'category_id', $category_id])->andWhere(['status' => 1])->andWhere(['not in', 'id', array($blog_id)])->asArray()->all();
        return $data;
    }

     /**
     * Function to get author list
     * 
     * @return array
     */
    protected function getAuthorList()
    {
        $users_list = ArrayHelper::map(User::find()->where(['=', 'status', 10])->all(), 'id', 'username');
        return $users_list;
    }

    /**
     * Function for comment
     *
     * @return mixed
     */
    public function actionComment($post_slug = '')
    {
        $model = new Comments();
        $post_slug = Yii::$app->request->get('id');
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->createdAt = date('Y-m-d');
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Your comment has been added successfully.');
                $this->refresh();
                return $this->redirect(['blog/view', 'id' => $model->blog_id]);
            } else {
                Yii::$app->session->setFlash('error', 'There was an error while adding comments.');
            }
        }
        return $this->redirect(['blog/view', 'id' => $post_slug]);
    }
}
