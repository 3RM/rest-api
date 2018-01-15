<?php

namespace common\modules\blog\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use common\components\behaviors\StatusBehavior;
use yii\web\UploadedFile;
use yii\image\drivers\Image;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use common\models\ImageManager;

/**
 * This is the model class for table "blog".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property string $url
 * @property integer $status_id
 * @property integer $sort
 * @property string $date_create
 * @property string $date_update
 * @property string $image
 */
class Blog extends \yii\db\ActiveRecord
{
    const STATUS_LIST = ['off', 'on'];
    const IMAGES_SIZE = [
        ['50','50'],
        ['800', NULL]
    ];
    public $tags_array;
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
                'value' => new Expression('NOW()'),
            ],
            /*'statusBehavior' => [
                'class' => StatusBehavior::className(),
                'statusList' => self::STATUS_LIST,
            ],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['text'], 'string'],
            [['status_id', 'sort'], 'integer'],
            [['sort'], 'integer', 'max' => 99, 'min'=> 1],
            [['title', 'url'], 'string', 'max' => 150],
            [['image'], 'string', 'max' => 100],
            [['file'], 'image'],
            [['url'], 'unique'],
            [['tags_array', 'date_create', 'date_update'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'text' => 'Текст',
            'url' => 'ЧПУ',
            'status_id' => 'Статус',
            'sort' => 'Сортировка',
            'tags_array' => 'Теги',
            'image' => 'Картинка',
            'file' => 'Картинка',
            'author.username' => 'Автор',
            'author.email' => 'Email автора',
            'tagsAsString' => 'Теги',
            'date_create' => 'Создано',
            'date_update' => 'Обновлено',
        ];
    }

    public function getImages()
    {
        return $this->hasMany(ImageManager::className(), ['item_id' => 'id'])->andWhere(['class' => self::tableName()])->orderBy('sort');
    }

    public function getImagesLinks()
    {
        return ArrayHelper::getColumn($this->images, 'imageUrl');
    }

    public function getImagesLinkData()
    {
        return ArrayHelper::toArray($this->images, [ImageManager::className() => [
            'caption' => 'name',
            'key' => 'id',
        ]]);
    }

    public function getAuthor()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    public function getBlogTag()
    {
        return $this->hasMany(BlogTag::className(), ['blog_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->via('blogTag');   
    }

    public function getTagsAsString()
    {
        $arr = ArrayHelper::map($this->tags, 'id','name');
        return implode(', ', $arr);
    }

    public static function getStatusList()
    {
        return self::STATUS_LIST;
    }

    public function getStatusName()
    {
        $list = self::getStatusList();
        return $list[$this->status_id];
    }

    public function getSmallImage()
    {
        if($this->image){
            $path = str_replace('admin.', '', \yii\helpers\Url::home(true)).'uploads/images/blog/50x50/'.$this->image;            
        }else{
            $path = str_replace('admin.', '', \yii\helpers\Url::home(true)).'uploads/images/nophoto.svg';
        }
        return $path;
        
    }

    public function afterFind()
    {
        //parent::getMyAfterFindStatus();
        $this->tags_array = $this->tags;
    }

    public function beforeSave($insert)
    {
        if($file = UploadedFile::getInstance($this, 'file')){
            $dir = Yii::getAlias('@images').'/blog/';
            if (!is_dir($dir . $this->image)) {
                if(file_exists($dir.$this->image)){
                    unlink($dir.$this->image);
                }
                if(file_exists($dir.'50x50/'.$this->image)){
                    unlink($dir.'50x50/'.$this->image);
                }
                if(file_exists($dir.'800x/'.$this->image)){
                    unlink($dir.'800x/'.$this->image);
                }
            }
            $this->image = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6).'.'.$file->extension;
            $file->saveAs($dir.$this->image);
            $imag = Yii::$app->image->load($dir.$this->image);
            $imag->background('#fff', 0);
            $imag->resize('50','50', Image::INVERSE);
            $imag->crop('50','50');
            if(!file_exists($dir.'50x50/')){
                FileHelper::createDirectory($dir.'50x50/');
            }
            $imag->save($dir.'50x50/'.$this->image, 90);

            $imag = Yii::$app->image->load($dir.$this->image);
            $imag->background('#fff', 0);
            $imag->resize('800',NULL, Image::INVERSE);
            if(!file_exists($dir.'800x/')){
                FileHelper::createDirectory($dir.'800x/');
            }
            $imag->save($dir.'800x/'.$this->image, 90);
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changeAttributes)
    {
        parent::afterSave($insert,$changeAttributes);

        $current_model_tags = ArrayHelper::map($this->tags,'id','id');

        if($this->tags_array){
            foreach($this->tags_array as $one)
            {
                if(!in_array($one,$current_model_tags)){
                    $model = new BlogTag();
                    $model->blog_id = $this->id;
                    $model->tag_id = $one;
                    $model->save();
                }
                if(isset($current_model_tags[$one])){
                    unset($current_model_tags[$one]);
                }            
            }
        }
        BlogTag::deleteAll(['tag_id' => $current_model_tags]);
    }

    public function beforeDelete()
    {        
        if(parent::beforeDelete()){
            $dir = Yii::getAlias('@images').'/blog/';
            if(file_exists($dir.$this->image)){
                @unlink($dir.$this->image);
            }
            foreach(self::IMAGES_SIZE as $size){
                $size_dir = $size[0].'x'; //50 or 800
                if($size[1] !== null){
                    $size_dir .= $size[1];
                }
                if(file_exists($dir.$size_dir.'/'.$this->image)){
                    @unlink($dir.$size_dir.'/'.$this->image);
                }                
            }
            //BlogTag::deleteAll(['blog_id' => $this->id]);
            foreach($this->blogTag as $one){
                $one->delete();
            } 
            foreach($this->images as $one){
                $one->delete();
                if(file_exists($dir.$one->name)){
                    unlink($dir.$one->name);
                }
            }           
            return true;
        }else{
            return false;
        }
    }
}
