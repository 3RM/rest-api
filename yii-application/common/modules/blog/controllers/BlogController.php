<?php

namespace common\modules\blog\controllers;

use Yii;
use common\modules\blog\models\Blog;
use common\modules\blog\models\BlogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\MethodNotAllowedHttpExeption;
use yii\filters\VerbFilter;
use common\models\ImageManager;
use yii\filters\AccessControl;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends Controller
{

    private $_model = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ['access' => [
            'class' => AccessControl::className(),
            'only' => ['create', 'update', 'delete'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'matchCallback' => function($rule,$action){
                        return Yii::$app->user->can('updatePost', ['author_id' => $this->findModelAuthorId(Yii::$app->request->get())]);
                    }                                                
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'matchCallback' => function($rule,$action){
                        return Yii::$app->user->can('updatePost', ['author_id' => $this->findModelAuthorId(Yii::$app->request->get())]);
                    }                                                
                ],
            ],
        ]];
    }
    
    protected function findModelAuthorId($id)
    {
        if ($this->_model === false) {
            $this->_model = Blog::findOne($id);
        }
        if ($this->_model !== null) {
            return $this->_model->user_id;
        }
        throw new NotFoundHttpException('Записи не существует');
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BlogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Blog();
        $model->sort = 50;



        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = \Yii::$app->user->id;
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Blog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteImage()
    {
        if($model = ImageManager::findOne(Yii::$app->request->post('key')) and $model->delete()){
            return true;
        }else{
            throw new NotFoundHttpException('The request page does not exists');
        }
    }

    public function actionSortImage($id)
    {
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post('sort');
            if($post['oldIndex'] > $post['newIndex']){
                $param = ['and', ['>=','sort', $post['newIndex']],['<','sort', $post['oldIndex']]];
                $counter = 1;
            }else{
                $param = ['and', ['<=','sort', $post['newIndex']],['>','sort', $post['oldIndex']]];
                $counter = -1;
            }
            ImageManager::updateAllCounters(['sort' => $counter],['and', ['class' => 'blog', 'item_id' => $id], $param]);
            ImageManager::updateAll(['sort' => $post['newIndex']],['id'=> $post['stack'][$post['newIndex']]['key']]);

            return true;
        }
        throw new MethodNotAllowedHttpExeption();
    }

    /**
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::find()->with('tags')->andWhere(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
