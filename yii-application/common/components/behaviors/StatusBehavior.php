<?php

namespace common\components\behaviors;

use yii\base\Behavior;

class StatusBehavior extends Behavior
{

	public $statusList;

	/*public function events()
    {
        return [
        	\yii\db\ActiveRecord::EVENT_AFTER_FIND => 'myFindStatus',
        ];
    }*/

	public function getStatusList()
    {
        return $this->statusList;
    }

    public function getStatusName()
    {
        $list = $this->owner->getStatusList();
        return $list[$this->owner->status_id];
    }

    /*public function getMyFindStatus()
    {
        return $this->owner->title .= $this->owner->status;
    }*/
}