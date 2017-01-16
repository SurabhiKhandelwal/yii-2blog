<?php namespace backend\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property integer $blog_id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property string $createdAt
 */
class Comments extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blog_id', 'name', 'email', 'message'], 'required'],
            [['blog_id'], 'integer'],
            [['message'], 'string'],
            [['createdAt'], 'safe'],
            [['name'], 'string', 'max' => 50],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'blog_id' => 'Blog ID',
            'name' => 'Name',
            'email' => 'Email',
            'message' => 'Message',
            'createdAt' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasOne(Posts::className(), ['id' => 'blog_id']);
    }
}
