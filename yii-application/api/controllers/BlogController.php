<?php

namespace api\controllers;

use Yii;
use common\models\Blog;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\helpers\Url;

class BlogController extends \yii\rest\ActiveController
{
	public $modelClass = 'common\models\Blog';

	public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
              HttpBasicAuth::className(),
              HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

	/*public function actionIndex()
	{
		$blogs = Blog::find()->with('author')->andWhere(['status_id' => 1]);
	}*/

	public function actionUserBlogs($id)
	{
		$blogs = Blog::find()->with('author')->andWhere(['user_id' => $id])->all();

		return $blogs;
	}

	public function actionCreate()
	{

		$model = new Blog;
		$model->user_id = Yii::$app->user->id;
		$model->load(Yii::$app->getRequest()->getBodyParams(), '');

		if($model->save()){
			$response = Yii::$app->getResponse();
			$response->setStatusCode(201);
			$id = implode(',', array_values($model->getPrimaryKey(true)));
			$response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
		}elseif(!$model->hasErrors()){
			throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
		}

		return $model;
	}

	public function checkAccess($action, $model = null, $params = [])
    {
    	if(in_array($action, ['update', 'delete'])){
    		if(!Yii::$app->user->can('updateOwnPost', ['author_id' => $model->user_id])){
    			throw new ForbiddenHttpException('Forbidden');
    		}
    	}
    }
}
