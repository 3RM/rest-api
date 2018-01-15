<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\base\DynamicModel;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'save-redactor-img', 'save-img'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index','rpc'],
                        'allow' => true,
                        'roles' => ['canAdmin']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public static function debug($arr){
        echo "<pre>".print_r($arr, true)."</pre>";
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRpc($method,$param)
    {
        $param = +$param;

        $server = 'http://phpxmlrpc.sourceforge.net/server.php';

        $request = xmlrpc_encode_request($method, [$param]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: text/xml',
                'content' => $request
            ],
        ]);

        $content = @file_get_contents($server, fales, $context);

        echo $request;

        $response = xmlrpc_decode($content);

        \backend\controllers\SiteController::debug($response);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSaveRedactorImg($sub = 'main')
    {
        $this->enableCsrfValidation = false;

        if(Yii::$app->request->isPost){
            $dir = Yii::getAlias('@images').'/'.$sub.'/';
            if(!file_exists($dir)){
                \yii\helpers\FileHelper::createDirectory($dir);
            }
            $result_link = str_replace('admin.', '', \yii\helpers\Url::home(true)).'uploads/images/'.$sub.'/';
            $file = UploadedFile::getInstanceByName('file');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image')->validate();

            if($model->hasErrors()){
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            }else{
                $model->file->name = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6).'.'.$model->file->extension;
                if($model->file->saveAs($dir . $model->file->name)){
                    $imag = Yii::$app->image->load($dir . $model->file->name);
                    $imag->resize(800, NULL, Yii\image\drivers\Image::PRECISE)
                    ->save($dir . $model->file->name, 85);
                    $result = [
                        'filelink' => $result_link . $model->file->name, 'filename' => $model->file->name
                    ];
                }else{
                    $result = [
                        'error' => Yii::t('vova07/imperavi', 'ERROR_CAN_NOT_UPLOAD_FILE')
                    ];
                }
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $result;
        }else{
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }

    public function actionSaveImg()
    {
        $this->enableCsrfValidation = false;


        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $dir = Yii::getAlias('@images').'/'.$post['ImageManager']['class'].'/';
            if(!file_exists($dir)){
                \yii\helpers\FileHelper::createDirectory($dir);
            }
            $result_link = str_replace('admin.', '', \yii\helpers\Url::home(true)).'uploads/images/'.$post['ImageManager']['class'].'/';
            $file = UploadedFile::getInstanceByName('ImageManager[attachment]');
            $model = new \common\models\ImageManager();
            $model->name = strtotime('now').'_'.Yii::$app->getSecurity()->generateRandomString(6).'.'.$file->extension;
            $model->load($post);
            $model->validate();

            if($model->hasErrors()){
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            }else{
                if($file->saveAs($dir . $model->name)){
                    $imag = Yii::$app->image->load($dir . $model->name);
                    $imag->resize(800, NULL, Yii\image\drivers\Image::PRECISE)
                    ->save($dir . $model->name, 85);
                    $result = [
                        'filelink' => $result_link . $model->name, 'filename' => $model->name
                    ];
                }else{
                    $result = [
                        'error' => 'Ошибка'
                    ];
                }
                $model->save();
            }
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return $result;
        }else{
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }
}
