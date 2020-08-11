<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

class Join extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email','password'],'required'],
            ['email','email'],
            ['email','unique','targetClass'=>'app\models\User'],
            ['password','string','min'=>2,'max'=>20]
        ];
    }

    public function signup()
    {
//        Заполняем все необходимые данные для регистрации пользователя.
        $user = new User();
        $user->email = $this->email;
        $user->status = 0;
        $user->token = uniqid('', true);
        $user->setPassword($this->password);
//        Затем пытаемся отправить письмо с подтверждением на указанный email. Если письмо отправлено, то заносим аккаунт в БД.
        if ($this->send_message($user->email, $user->token)){
            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('user');
            $auth->assign($authorRole, $user->getId());
            return $user->save();
        }
        else return false;
    }

    public function send_message($email, $token){
//        Формируем само письмо.
        $absoluteHomeUrl = Url::home(true); //http://сайт
        $serverName = Yii::$app->request->serverName; //сайт без http
        $url = $absoluteHomeUrl.'activation/'.$token;

        $msg = "Hello! Thank you for using the site $serverName! You only need to confirm your email. To do this, follow the link: $url";

        $msg_html  = "<html><body style='font-family:Arial,sans-serif;'>";
        $msg_html .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Hello! Thank you for using the site <a href='". $absoluteHomeUrl ."'>$serverName</a></h2>\r\n";
        $msg_html .= "<p><strong>You only need to confirm your email.</strong></p>\r\n";
        $msg_html .= "<p><strong>To do this, follow the link: </strong><a href='". $url."'>$url</a></p>\r\n";
        $msg_html .= "</body></html>";

//        Отправляем письмо на указанный адрес, в случае неудачи выводим сообщение.
        try{
            Yii::$app->mailer->compose()
                ->setFrom('TodoList.adm@yandex.ru')
                ->setTo($email)
                ->setSubject('Account Verification')
                ->setTextBody($msg)
                ->setHtmlBody($msg_html)
                ->send();
        }
        catch (Exception $e){
            Yii::$app->session->setFlash('error', "Cannot send an account confirmation email to this email address. Try again.");
        }
        return true;
    }

    public function confirm($token){
//        Меняем статус аккаунта на подтвержденный в случае перехода по ссылке в письме.
        $user = $this->getUserByToken($token);
        if ($user){
            $user->token = '';
            $user->status = 1;
            return $user->save();
        }
        else return false;
    }

    public function getUser()
    {
        return User::findOne(['email'=>$this->email]);
    }

    public function getUserByToken($token)
    {
        return User::findOne(['token'=>$token]);
    }
}