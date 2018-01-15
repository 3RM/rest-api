<?php

namespace common\models\query;

use common\models\User;
use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
	public function active()
	{
		return $this->andWhere(['status' => User::STATUS_ACTIVE]);
	}

	public function all($db = null)
	{
		return parent::all($db);
	}

	public function one($db = null)
	{
		return parent::one($db);
	}
}