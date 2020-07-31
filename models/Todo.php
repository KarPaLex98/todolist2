<?php

namespace app\models;

use Exception;
use Yii;
use yii\helpers\Url;

class Todo extends \yii\db\ActiveRecord
{
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            ['title','string','min'=>1,'max'=>255],
            ['description','string','min'=>1,'max'=>255],
        ];
    }

    public static function tableName()
    {
        return 'todo';
    }

    public static function getAll()
    {
//        Получение выборки всех задач для пользователя с заданным id
        $data = self::find()->where(['id_user' => Yii::$app->user->getId()])->all();
        return $data;
    }

    public static function getAllTasks()
    {
//        Получение выборки всех задач
        $data = self::find()->all();
        return $data;
    }

    public static function getOne($id)
    {
//        Получение одной конкретной задачи по ее id
        $data = self::find()->where(['id' => $id])->one();
        return $data;
    }

    public static function SendAllMessage()
    {
        $AllTasks = self::find()->all();
        $now = date('Y-m-d H:i', time());
        echo $now;
        foreach ($AllTasks as $task){
            echo $task->date_time;
            if (substr ($task->date_time,0, 16) == $now){
                $user_task = User::findIdentity($task->id_user);
                $email = $user_task->email;
                $title = $task->title;
                $description = $task->description;
                if ($email != NULL && $task->is_Send == 0) {
                    $task->is_Send = 1;
                    $task->save();
                    $msg = "Reminder! Title: $title. Description: $description.";
                    $msg_html  = "<html><body style='font-family:Arial,sans-serif;'>";
                    $msg_html .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Reminder</h2>\r\n";
                    $msg_html .= "<p><strong>Title: $title.</strong></p>\r\n";
                    $msg_html .= "<p><strong>Description: $description.</strong></p>\r\n";
                    $msg_html .= "</body></html>";

                    Yii::$app->mailer->compose()
                            ->setFrom('TodoList.adm@yandex.ru')
                            ->setTo($email)
                            ->setSubject('Reminder')
                            ->setTextBody($msg)
                            ->setHtmlBody($msg_html)
                            ->send();
//                    sleep(20);
                }
            }
        }
    }
}
