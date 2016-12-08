<?php

namespace backend\modules\facturas\controllers;

use Yii;
use common\models\Facturas;
use backend\models\buscadores\FacturasBuscador;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * FacturasController implements the CRUD actions for Facturas model.
 */
class FacturasController extends Controller
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
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create','view','update'],
                        'allow' => true,
                        'roles' => ['vendedor'],
                    ],
                    [
                        'actions' => ['create','view','update'],
                        'allow' => true,
                        'roles' => ['empleado'],
                    ],
                    [
                        'actions' => ['update','create','view','delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Facturas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacturasBuscador();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Facturas model.
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
     * Creates a new Facturas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('crearFacturas')) {
            $model = new Facturas();

            if ($model->load(Yii::$app->request->post())) {
                $model->empleado_id = Yii::$app->user->id;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Facturas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //valida si son sus facturas o de otro usuario
        //si el usuario tiene ambos o uno de los dos permisos puede editar la factura
        $validaFacturasPropias = Yii::$app->user->can('editarFacturasPropias', ['factura' => $model]);
        $validaFacturasAjenas = Yii::$app->user->can('editarFacturasAjenas', ['factura' => $model]);

        if ($validaFacturasPropias || $validaFacturasAjenas) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        Yii::$app->session->setFlash('error', Yii::t('app','No tienes permisos para editar factura: {factura}', [
            'factura'=> $model->codigo
        ]));
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Facturas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('eliminarFacturas')) {
            $model->delete();
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('error', Yii::t('app','No tienes permisos para eliminar factura: {factura}', [
            'factura'=> $model->codigo
        ]));
        return $this->redirect(['index']);
    }

    /**
     * Finds the Facturas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facturas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facturas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
