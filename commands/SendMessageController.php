<?php

namespace app\commands;

use app\models\Todo;
use yii\console\Controller;
use yii\console\ExitCode;

class SendMessageController extends Controller
{
    public function actionIndex()
    {
        $now = date('Y-m-d H:i', time());
        echo $now;
        $todo_list = Todo::SendAllMessage();
        return ExitCode::OK;
    }
}
