<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\form\LaporanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Data Pantauan Posko');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php 
    $getSelesaiPemantauan = \app\models\DataPoskoModel::getSelesaiPemantauan();
?>
<?php if($getSelesaiPemantauan):?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Data Warga Melewati Ketentuan Karantina Mandiri</span>
              <span class="info-box-number"><?=$getSelesaiPemantauan;?> Warga <a class="btn btn-xs btn-primary" href="<?= \yii\helpers\Url::toRoute(['/dataposko','waktu'=>'selesai']);?>">Lihat Data Warga</a></span>

              <div class="progress">
                <div class="progress-bar" style="width: 100%"></div>
              </div>
                  <span class="progress-description">
                    <?=$getSelesaiPemantauan;?> Warga Telah Melewati Masa Pemantauan dan status belum diubah, mohon divalidasi kembali.
                  </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
    </div>    
</div>
<?php endif;?>

<div class="laporan-model-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', '<i class="fa fa-plus"></i> Buat Data Pantauan Posko Baru'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php if(\yii::$app->request->url=="/index.php/dataposko" or \yii::$app->request->url=="/dataposko"):?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-print"></i> Cetak PDF'), [str_replace('index.php', '', \yii::$app->request->url),'cetak'=>true], ['class' => 'btn btn-primary btn-flat','data-pjax'=>0,'target'=>'__blank']) ?>
        <?php else:?>
            <?= Html::a(Yii::t('app', '<i class="fa fa-print"></i> Cetak PDF'), [str_replace('index.php', '', \yii::$app->request->url).'&cetak=TRUE'], ['class' => 'btn btn-primary btn-flat','data-pjax'=>0,'target'=>'__blank']) ?>
        <?php endif;?>

    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                [
                    'attribute' => 'jenis_laporan',
                    'value' => function ($model) {
                        return $model->jenisLaporanText;
                    },
                    'filter' => \app\models\JenisLaporanModel::getJenisLaporanList(),
                    'filterInputOptions' => ['prompt' => 'Semua Jenis Laporan', 'class' => 'form-control', 'id' => null]
                ],
                // 'jenis_laporan',
                'nama_warga',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return ($model->statusDetail) ? $model->statusDetail : null;
                    },
                    'format'=>'raw',
                    'filter' => \app\models\DataPoskoModel::getStatusList(),
                    'filterInputOptions' => ['prompt' => 'Semua Status', 'class' => 'form-control', 'id' => null]
                ],
                // [
                //     'attribute' => 'kelurahan',
                //     'value' => function ($model) {
                //         return ($model->kelurahanBelongsToKelurahanModel) ? implode(' - ', [$model->kelurahanBelongsToKelurahanModel->nama,$model->kelurahanBelongsToKelurahanModel->kelurahanBelongsToKecamatanModel->nama,$model->kelurahanBelongsToKelurahanModel->kelurahanBelongsToKecamatanModel->kecamatanBelongsToKabupatenModel->nama]) : null;
                //     },
                //     'filter' => Select2::widget([

                //         'model' => $searchModel,

                //         'attribute' => 'kelurahan',

                //         // 'data' => Object::typeNames(),

                //         'theme' => Select2::THEME_BOOTSTRAP,

                //         'hideSearch' => true,
                //         'initValueText' => \app\models\KelurahanModel::getTextKelurahanById($searchModel->kelurahan),                        
                //         'options' => [

                //             'placeholder' => 'Pilih Kelurahan/Desa ...',

                //         ],
                //         'pluginOptions' => [
                //             'allowClear' => true,
                //             'minimumInputLength' => 4,
                //             'language' => [
                //                 'errorLoading' => new JsExpression("function () { return 'Sedang mencari data...'; }"),
                //             ],
                //             'ajax' => [
                //                 'url' => \yii\helpers\Url::to(['/site/getdatakelurahan']),
                //                 'dataType' => 'json',
                //                 'data' => new JsExpression('function(params) { return {q:params.term}; }')
                //             ],
                //         ],

                //     ]),
                //     // 'filterInputOptions' => ['prompt' => 'All Categories', 'class' => 'form-control', 'id' => null]
                // ],
                // 'alamat',
                // 'no_telepon_pelapor',
                // 'no_telepon_terlapor',
                [
                    'attribute' => 'kota_asal',
                    'value' => function ($model) {
                        return $model->kotaAsalText;
                    },
                    'filter' => Select2::widget([

                        'model' => $searchModel,

                        'attribute' => 'kota_asal',

                        // 'data' => Object::typeNames(),

                        'theme' => Select2::THEME_BOOTSTRAP,

                        'hideSearch' => true,
                        'initValueText' => \app\models\KabupatenModel::getTextKabById($searchModel->kota_asal),                        
                        'options' => [

                            'placeholder' => 'Pilih Kota/Kab ...',

                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 4,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Sedang mencari data...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/site/getdatakabupaten']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],

                    ]),
                    // 'filterInputOptions' => ['prompt' => 'All Categories', 'class' => 'form-control', 'id' => null]
                ],                
                [
                    'attribute' => 'kelurahan_datang',
                    'value' => function ($model) {
                        return $model->kelurahanDatangText;
                    },
                    'filter' => Select2::widget([

                        'model' => $searchModel,

                        'attribute' => 'kelurahan_datang',

                        // 'data' => Object::typeNames(),

                        'theme' => Select2::THEME_BOOTSTRAP,

                        'hideSearch' => true,
                        'initValueText' => \app\models\KelurahanModel::getTextKelurahanById($searchModel->kelurahan),                        
                        'options' => [

                            'placeholder' => 'Pilih Kelurahan/Desa ...',

                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 4,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Sedang mencari data...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/site/getdatakelurahan']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],

                    ]),
                    // 'filterInputOptions' => ['prompt' => 'All Categories', 'class' => 'form-control', 'id' => null]
                ],
                // 'keterangan:ntext',
                // 'id_pelapor',
                [
                    'attribute' => 'id_posko',
                    'value' => function ($model) {
                        return $model->poskoText;
                    },
                    'filter' => Select2::widget([

                        'model' => $searchModel,

                        'attribute' => 'id_posko',

                        // 'data' => Object::typeNames(),

                        'theme' => Select2::THEME_BOOTSTRAP,

                        'hideSearch' => true,
                        'initValueText' => \app\models\PoskoModel::getTextPoskoById($searchModel->kelurahan),                        
                        'options' => [
                            'placeholder' => 'Pilih Posko ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 4,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Sedang mencari data...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['/site/getdataposko']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                        ],

                    ]),
                    // 'filterInputOptions' => ['prompt' => 'All Categories', 'class' => 'form-control', 'id' => null]
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '#',
                    'headerOptions' => ['style' => 'color:#337ab7;text-align:center;'],
                    'template' => '{view} {update} {delete}',
                    'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="fa fa-eye"></span> Detail', $url, [
                                            'title' => Yii::t('app', 'view'),
                                            'class'=>'btn btn-success btn-xs modal-form',
                                            'data-size' => 'modal-lg',
                                            'data-pjax'=>0,
                                ]);
                            },

                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-pencil"></span> Ubah', $url, [
                                            'title' => Yii::t('app', 'update'),
                                            'class'=>'btn btn-warning btn-xs modal-form',
                                            'data-size' => 'modal-lg',

                                ]);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span> Hapus', $url, [
                                            'title' => Yii::t('app', 'delete'),
                                            'class'=>'btn btn-danger btn-xs modal-form',
                                            'data-method'=>'post',
                                            'data-confirm'=>'Apakah anda yakin akan menghapus data ini ? ',
                                ]);
                            }
                    ],
                    // 'urlCreator' => function ($action, $model, $key, $index) {
                    //     if ($action === 'view') {
                    //         $url ='view?id='.$model->id;
                    //         return $url;
                    //     }

                    //     if ($action === 'update') {
                    //         $url ='update?id='.$model->id;
                    //         return $url;
                    //     }
                    // }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
