<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
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
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRole(){
        /*$admin = Yii::$app->authManager->createRole('admin');
        $admin->description = 'Администратор';
        Yii::$app->authManager->add($admin);

        $user = Yii::$app->authManager->createRole('user');
        $user->description = 'Пользователь';
        Yii::$app->authManager->add($user);

        $content = Yii::$app->authManager->createRole('content');
        $content->description = 'Контент менеджер';
        Yii::$app->authManager->add($content);

        $ban = Yii::$app->authManager->createRole('banned');
        $ban->description = 'Заблокированый пользователь';
        Yii::$app->authManager->add($ban);*/

        /*$permit2 = Yii::$app->authManager->createPermission('updateOwnPost');
        $permit2->description = 'Право редактировать свой пост';
        Yii::$app->authManager->add($permit2);

        $permit3 = Yii::$app->authManager->createPermission('updatePost');
        $permit3->description = 'Право редактировать пост';
        Yii::$app->authManager->add($permit3);*/

        /*$role_a = Yii::$app->authManager->getRole('admin');
        $role_c = Yii::$app->authManager->getRole('content');
        $permit = Yii::$app->authManager->getPermission('canAdmin');
        Yii::$app->authManager->addChild($role_a, $permit);
        Yii::$app->authManager->addChild($role_c, $permit);*/


        /*$role_a = Yii::$app->authManager->getRole('admin');        
        $permit1 = Yii::$app->authManager->getPermission('updatePost');*/


        /*$permit1 = Yii::$app->authManager->getPermission('updateOwnPost');        
        $permit2 = Yii::$app->authManager->getPermission('updatePost');

        Yii::$app->authManager->addChild($permit1, $permit2);

        $permit3 = Yii::$app->authManager->getRole('content');        
        $permit4 = Yii::$app->authManager->getPermission('updateOwnPost');

        Yii::$app->authManager->addChild($permit3, $permit4);*/
        

        /*$userRole2 = Yii::$app->authManager->getRole('content');
        Yii::$app->authManager->assign($userRole2, 2);*/


        /*$auth = Yii::$app->authManager;
        $rule = new \common\rules\AuthorRule();
        //$auth->add($rule);

        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Редактировать посты';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);*/


        
        /*$auth = Yii::$app->authManager;
        $rule = new \common\rules\BlogRule();
        $auth->add($rule);

        $updateOwnPost = $auth->createPermission('updateOwnBlog');
        $updateOwnPost->description = 'Редактировать блог';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);*/
        
        return "Created"; 
    }
}
