<?php

namespace common\rules;

use yii\rbac\Rule;

class BlogRule extends Rule
{
	public $name = 'isBlogOwner';

	public function execute($user_id, $item, $params){
		if(isset($params['author_id']) and ($params['author_id'] == $user_id)){
			return true;
		}else{
			return false;
		}
	}
}

?>