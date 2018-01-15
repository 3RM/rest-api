<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use api\models\LoginForm;
use yii\web\Response;

class SiteController extends Controller
{
    public function actionIndex()
    {
        //Yii::$app->response->format = Response::FORMAT_JSON;
        return 'api';
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            //переместили этот массив в Token в метод fields, для возврата токена и его время жизни, после того как пользователь залогинился 
            /*return [
                'token' => $token->token,
                'expired' => date(DATE_RFC3339, $token->expired_at),
            ];*/
            return $token;
        } else {
            return $model;
        }
    }

    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }
}