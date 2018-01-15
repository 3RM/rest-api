<?php

namespace api\controllers;

use api\models\PostSearch;
use common\models\Post;
use common\rbac\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class ProductController extends ActiveController
{
    public $modelClass = 'common\models\Product';

    public function actionTest()
    {
        $val = Yii::$app->getRequest()->getBodyParams();
        
        var_dump($val);
    }
}