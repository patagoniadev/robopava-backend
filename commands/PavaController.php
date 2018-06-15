<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use GuzzleHttp\Client;
use yii\helpers\VarDumper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\api\modules\v1\models\Pava;

class PavaController extends Controller
{
    public function actionCalentar($temperatura)
    {
        $pava = new Pava();
        $pava->calentar($temperatura);
    }

    public function actionTemperatura()
    {
        $pava = new Pava();
        $temperatura = $pava->temperatura();
        Console::output($temperatura);
    }

    public function actionUsers()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://api.robopava.localhost/v1/users');

        $users = Json::decode($response->getBody());

        foreach ($users as $user) {
            Console::output('User: ' . $user['id'] . ' {');
            Console::output("\tusername: " . $user['username']);
            Console::output("\temail: " . $user['email']);
            Console::output('}');
        }
    }
}