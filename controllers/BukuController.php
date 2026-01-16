<?php

namespace app\controllers;

use app\models\Buku;
use app\models\BukuSearch;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BukuController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !\Yii::$app->user->isGuest && \Yii::$app->user->identity->tipe_user === 'admin';
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BukuSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Buku();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Buku berhasil ditambahkan.');
                return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error', 'Gagal menambahkan buku: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        // Jika dari modal, redirect ke index
        if (\Yii::$app->request->isPost) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', 'Buku berhasil diperbarui.');
                return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error', 'Gagal memperbarui buku: ' . implode(', ', $model->getFirstErrors()));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $judul = $model->judul;
        
        try {
            $model->delete();
            \Yii::$app->session->setFlash('success', 'Buku "' . $judul . '" berhasil dihapus.');
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id): Buku
    {
        if (($model = Buku::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

