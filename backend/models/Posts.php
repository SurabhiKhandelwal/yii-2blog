<?php namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use common\models\User;

/**
 * This is the model class for table "blog_posts".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $author
 * @property string $tags
 * @property integer $category_id
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property string $publish_date
 */
class Posts extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'author', 'tags', 'category_id', 'description', 'status', 'publish_date'], 'trim'],
            [['title', 'author', 'tags', 'category_id', 'description', 'status', 'publish_date'], 'required', 'on' => ['create', 'update']],
            [['id', 'category_id', 'status'], 'integer'],
            [['tags'], 'string'],
            [['image'], 'safe'],
            [['image'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['png', 'jpg', 'jpeg', 'gif'],
                'when' => function ($model, $attribute) {
                return $model->{$attribute} !== $model->getOldAttribute($attribute);
            },
                'on' => 'update'
            ],
            [['title', 'slug', 'author'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['publish_date'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'author' => 'Author',
            'tags' => 'Tags',
            'category_id' => 'Category ID',
            'description' => 'Description',
            'image' => 'Image',
            'status' => 'Status',
            'publish_date' => 'Publish Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['blog_id' => 'id']);
    }

    public function deleteImage($image_name = '')
    {
        $final_name = empty($image_name) ? $this->image : $image_name;
        $image = $this->getImageFile($final_name);

        // check if file exists on server
        if (empty($image) || !file_exists($image)) {
            return false;
        }

        // unlink from the folder
        if (unlink($image)) {
            if (!empty($image_name)) {
                $this->image = null;
                $this->save();
            }
            return true;
        }
        return false;
    }

    /**
     * fetch stored image file name with complete path 
     * @return string
     */
    public function getImageFile($filename)
    {
        return isset($filename) ? Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $filename : null;
    }

    public function getAuthorName($author)
    {
        $user_name = User::find()->select(['username'])->where(['=', 'id', $author])->asArray()->all();
        if (count($user_name) > 0) {
            return $user_name[0]['username'];
        }
        return 'NA';
    }
}
