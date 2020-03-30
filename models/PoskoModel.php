<?php

namespace app\models;

use Yii;
use app\models\table\PoskoTable;
use yii\helpers\ArrayHelper;

class PoskoModel extends PoskoTable
{

    const STATUS_DELETED = 20;
    const STATUS_ACTIVE = 10;
    const STATUS_SUSPENDED = 30;

    public function getStatusDetail()
    {
        $status = $this->status;
        $array = self::getStatusList();
        return isset($array[$status]) ? $array[$status] : NULL;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE=>"ACTIVE",
            self::STATUS_SUSPENDED=>"SUSPENDED",
            self::STATUS_DELETED=>"DELETED",
        ];
    }

    public static function getPoskoCount()
    {
    	return self::find()->count();
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'id_kelurahan' => Yii::t('app', 'Kelurahan / Desa'),
            'nama_posko' => Yii::t('app', 'Nama Posko'),
            'alamat_posko' => Yii::t('app', 'Alamat Posko'),
            'email_posko' => Yii::t('app', 'Email Posko'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

	public static function getTextPoskoById($id)
	{
		$model = self::find()->where(['id'=>$id])->one();
		if($model)
		{
            $nama_posko = $model->nama_posko;
            $kelurahan = $model->poskoBelongsToKelurahanModel->nama;
            $kecamatan = $model->poskoBelongsToKelurahanModel->kelurahanBelongsToKecamatanModel->nama;
			return implode(' - ', [$nama_posko,$kelurahan,$kecamatan]);
		}
		else
		{
			return $id;
		}
	}

	public function getPoskoBelongsToKelurahanModel()
	{
		return $this->hasOne(KelurahanModel::className(),['id_kel'=>'id_kelurahan']);
	}

    public static function getPoskoList()
    {
        $model = self::find()->all();
        if ($model)
        {
            return ArrayHelper::map ($model, 'id', 'nama_posko');
        }
    }

}