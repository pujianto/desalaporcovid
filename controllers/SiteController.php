<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionGetposko()
    {
        echo '{"output":[{"id":1,"name":"eBooks"},{"id":2,"name":"Music"},{"id":3,"name":"Movies"},{"id":4,"name":"Games"},{"id":5,"name":"Stationery"}],"selected":""}';
        // if (isset($_POST['depdrop_parents'])) {
        //     // $parents = $_POST['depdrop_parents'];
        //     echo "<pre>";            
        //     print_r($_POST);
        //     echo "</pre>";
        //     die;            
        // }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $out[] = [
                    1 => 'El',
                    2 => 'Bo',
                    3 => 'Ho'
                ];
                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return ['output'=>$out, 'selected'=>1];
        // Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // $out = [];
        // if (isset($_POST['depdrop_parents'])) {
        //     $parents = $_POST['depdrop_parents'];
        //     if ($parents != null) {
        //         // $cat_id = $parents[0];
        //         $out = [
        //             1 => 'Electronics',
        //             2 => 'Books',
        //             3 => 'Home & Kitchen'
        //         ];
        //         // the getSubCatList function will query the database based on the
        //         // cat_id and return an array like below:
        //         // [
        //         //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
        //         //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
        //         // ]
        //         return ['output'=>$out, 'selected'=>''];
        //     }
        // }
        // return ['output'=>'', 'selected'=>''];
    }

    public function actionGetdatanegara($q=null,$id=null)
    {
        if(!is_null($q)) 
        {
            $model = \app\models\NegaraModel::find()
                ->where(['like','nama',$q])
                ->all();
            if($model and strlen($q)>3)
            {
                foreach($model as $modelData)
                {
                    $nama = $modelData->nama;
                    $kode_negara = $modelData->kode_negara;
                    $returnData[] = [
                        'id'=>$modelData->id,
                        'text'=>implode(' - ',[$nama,$kode_negara]),
                    ];
                }
            }
            else
            {
                $returnData = [];
            }
        }
        elseif($id > 0)
        {
            $model = \app\models\NegaraModel::find()->where(['id'=>$id])->one();
            if($model)
            {
                $nama = $model->nama;
                $kode_negara = $model->kode_negara;
                $returnData = [
                    'id'=>$model->id,
                    'text'=>implode(' - ',[$nama,$kode_negara]),
                ];
            }
            else
            {
                $returnData = [];               
            }
        }
        $returnDatas['results'] = $returnData;
        return json_encode($returnDatas);
    }

    public function actionGetdatakelurahan($q=null,$id=null)
    {
        if(!is_null($q)) 
        {
            $model = \app\models\KelurahanModel::find()
                ->where(['like','nama',$q])
                ->all();
            if($model and strlen($q)>3)
            {
                foreach($model as $modelData)
                {
                    $kelurahan = $modelData->nama;
                    $kecamatan = $modelData->kelurahanBelongsToKecamatanModel->nama;
                    $kabupaten = $modelData->kelurahanBelongsToKecamatanModel->kecamatanBelongsToKabupatenModel->nama;
                    $returnData[] = [
                        'id'=>$modelData->id_kel,
                        'text'=>implode(' - ',[$kelurahan,$kecamatan,$kabupaten]),
                    ];
                }
            }
            else
            {
                $returnData = [];
            }
        }
        elseif($id > 0)
        {
            $model = \app\models\KelurahanModel::find()->where(['id_kel'=>$id])->one();
            if($model)
            {
                $kelurahan = $model->nama;
                $kecamatan = $model->kelurahanBelongsToKecamatanModel->nama;
                $kabupaten = $model->kelurahanBelongsToKecamatanModel->kecamatanBelongsToKabupatenModel->nama;
                $returnData = [
                    'id'=>$model->id_kel,
                'text'=>implode(' - ',[$kelurahan,$kecamatan,$kabupaten]),
                ];
            }
            else
            {
                $returnData = [];               
            }
        }
        $returnDatas['results'] = $returnData;
        return json_encode($returnDatas);
    }

    public function actionGetdatakabupaten($q=null,$id=null)
    {
        if(!is_null($q)) 
        {
            $model = \app\models\KabupatenModel::find()
                ->where(['like','nama',$q])
                ->all();
            if($model and strlen($q)>3)
            {
                foreach($model as $modelData)
                {
                    $kabupaten = $modelData->nama;
                    $returnData[] = [
                        'id'=>$modelData->id_kab,
                        'text'=>implode(' - ',[$kabupaten]),
                    ];
                }
            }
            else
            {
                $returnData = [];
            }
        }
        elseif($id > 0)
        {
            $model = \app\models\KabupatenModel::find()->where(['id_kab'=>$id])->one();
            if($model)
            {
                $kabupaten = $model->nama;
                $returnData = [
                    'id'=>$model->id_kab,
                    'text'=>implode(' - ',[$kabupaten]),
                ];
            }
            else
            {
                $returnData = [];               
            }
        }
        $returnDatas['results'] = $returnData;
        return json_encode($returnDatas);
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

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
