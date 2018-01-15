<?php

namespace frontend\controllers;

use Yii;
use common\models\Blog;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class BlogController extends Controller
{
	public function actionIndex()
	{
		$blogs = Blog::find()->with('author')->andWhere(['status_id' => 1]);

		$dataProvider = new ActiveDataProvider([
            'query' => $blogs,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
        
        return $this->render('all', compact('dataProvider'));
	}

	public function actionOne($url)
	{
		if($blog = Blog::find()->andWhere(['url' => $url])->one())
		{
			return $this->render('one', compact('blog'));
		}
        throw new NotFoundHttpException('Ой..., нет такого блога');
        
	}
}