<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $sklad_id
 * @property string $title
 * @property int $cost
 * @property int $type_id
 * @property string $desc
 */
class Product extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date']
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sklad_id', 'type_id'], 'required'],
            [['sklad_id', 'cost', 'type_id'], 'integer'],
            [['desc'], 'string'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sklad_id' => 'Sklad ID',
            'title' => 'Title',
            'cost' => 'Cost',
            'type_id' => 'Type ID',
            'desc' => 'Desc',
            'date' => 'Date'
        ];
    }

    public static function getTypeList(){
        return [
            'первый','второй','третий'
        ];
    }

    public function getSklad(){
        return $this->hasOne(Sklad::className(),['id' => 'sklad_id']);
    }

    public function extraFields()
    {
        return [
            'sklad' => 'sklad',
        ];
    }

    public function getSkladName(){
        return (isset($this->sklad)) ? $this->sklad->title : 'не задан';
    }

    public function getTypeName(){
        $list = $this->getTypeList();
        return $list[$this->type_id];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'sklad_id' => function ($model) {
                                return $model->sklad->title;
                            }
        ];
    }

}
