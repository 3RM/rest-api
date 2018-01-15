<?php

namespace common\modules\blog\models;

use Yii;

/**
 * This is the model class for table "time".
 *
 * @property int $id
 * @property int $time
 * @property string $date
 * @property string $datetime
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'time';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time'], 'string'],
            [['date', 'datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'date' => 'Date',
            'datetime' => 'Datetime',
        ];
    }
}
