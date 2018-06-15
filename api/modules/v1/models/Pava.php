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
    public function init()
    {
        parent::init();
        $this->ip = '10.15.4.93';
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

    public function temperatura()
    {
        $ip = $this->ip;
        $client = new Client();

        $response = $client->request(
            'POST',
            'http://' . $ip . '/temperatura-actual'
        );

        $body = $response->getBody();
        yii::debug($body);

        return $body;
    }

    public function calentar($temperatura)
    {
        $ip = $this->ip;
        $client = new Client();

        $response = $client->request(
            'POST',
            'http://' . $ip . '/calentar',
            [
                'headers' => ['Content-Type' => 'x-www-form-urlencoded'],
                'json' => ['temperatura' => $temperatura],
                'timeout' => 5
            ]
        );
        $body = $response->getBody();
        yii::debug($body);
    }
}