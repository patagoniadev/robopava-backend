<?php
namespace app\api\modules\v1\models;

use Yii;
use yii\web\Link;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\Linkable;
use GuzzleHttp\Client;
use yii\helpers\Console;
use yii\helpers\VarDumper;

class Pava extends \app\models\Pava implements Linkable
{
    const ENDPOINT_CALENTAR = '/calentar';
    const ENDPOINT_TEMPERATURA = '/temperatura-actual';
    const TIMEOUT = 5;

    public $urlCalentar;
    public $urlTemperatura;

    public function init()
    {
        parent::init();
        $this->ip = 'localhost:6080';
        $this->urlCalentar = 'http://' . $this->ip . self::ENDPOINT_CALENTAR;
        $this->urlTemperatura = 'http://' . $this->ip . self::ENDPOINT_TEMPERATURA;
    }

    public function fields()
    {
        return ['id', 'ip'];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['pava/view', 'id' => $this->id], true),
            'index' => Url::to(['pava/index'], true),
            'temperatura' => Url::to(['pava/temperatura', 'id' => $this->id], true),
        ];
    }

    private function _makeRequest($method = 'POST', $url, $config)
    {
        $client = new Client();
        $response = $client->request($method, $url, $config);
        return $response;
    }

    public function temperatura()
    {
        $url = $this->urlTemperatura;
        $config = ['timeout' => self::TIMEOUT];

        $cached = Yii::$app->cache->get('temperatura');
        if($cached) {
            return $cached;
        }

        $temperatura = (int) $this
            ->_makeRequest('POST', $url, $config)
            ->getBody()
            ->getContents();

        yii::info('Nueva Temperatura: ' . $temperatura);
        Yii::$app->cache->set('temperatura', $temperatura, 5);

        return $temperatura;
    }

    public function calentar($temperatura)
    {
        $url = $this->urlCalentar;

        $config = [
            'headers' => ['Content-Type' => 'x-www-form-urlencoded'],
            'json' => ['temperatura' => $temperatura],
            'timeout' => self::TIMEOUT
        ];

        $response = $this->_makeRequest('POST', $url, $config)->getBody();
        yii::debug($response);

        return $response;
    }
}