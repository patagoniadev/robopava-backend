<?php
namespace app\api\modules\v1\controllers;

use Yii;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\base\UserException;
use yii\rest\ActiveController;
use app\api\modules\v1\models\Pava;
use yii\web\ServerErrorHttpException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class PavaController extends ActiveController
{
    public $modelClass = 'app\api\modules\v1\models\Pava';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter']['class'] = \yii\filters\Cors::className();
        return $behaviors;
    }

    public function actionTemperatura()
    {
        $pava = new Pava();

        try {
            $temperatura = $pava->temperatura();
            return $temperatura;
        } catch (RequestException $e) {
            throw new ServerErrorHttpException('Pava inaccesible');
        }
    }

    public function actionCalentar()
    {
        $params = Yii::$app->request->getBodyParams();
        yii::error(VarDumper::dumpAsString($params));
        $temperatura = (int) $params['temperatura'];
        
        if($temperatura < 0 || $temperatura > 100) {
            $mensaje = VarDumper::dumpAsString($temperatura);
            yii::error('Temperatura no válida: ' . $mensaje);
            return 'Temperatura no válida: ' . $mensaje;
        }
        
        $pava = new Pava();

        try {
            $pava->calentar($temperatura);
        } catch (RequestException $e) {
            throw new ServerErrorHttpException('Pava inaccesible');
        }

        return 'Calentando...';
    }
}