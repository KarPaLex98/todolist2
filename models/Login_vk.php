<?php

namespace app\models;


use yii\base\Model;

class Login_vk extends Model
{
    public $email;
    public $user_id;

    public function validation()
    {
//        Проверяем зарегистрирован ли пользователь, желающий авторизоваться
        $user = $this->getUser();
        if (!$user) return true;
    }

    public function signup_vk()
    {
        if ($this->validation()){
//            Если пользователь не зарегестрирован, то заносим его данные в БД и авторизовываем в контроллере
            $user = new User();
            $user->email = $this->email;
            $user->vk_id = $this->user_id;
            $user->status = 1;
            $user->token = '';
            $user->setPassword('');
            return $user->save();
        }
        else{
//            Если пользователь зарегистрирован, то проверяем, сопадает ли email его аккаунта в БД с тем, который вернул VK.
//            Если совпадает, то авторизовываем пользователя в контроллере.
//            Если не совпадает, то проверяем, есть ли уже в БД аккаунты с email, который передал VK. Если есть, то просто
//            авторизовываем пользователя в контроллере, если же аккаунта с таким email нет, то обновляем БД, дописывая email
//            к аккаунту пользователя и потом аторизовываем.
            $user = $this->getUserByVk_id();
            if ($user->email != $this->email){
                if ($this->getUserByEmail()->email == ''){
                    $user->email = $this->email;
                    return $user->save();
                }
            }
        }
        return true;
    }

    public function getAccessToken($code){
//        Отправляем запрос на получение access_token с помощью cURL, чтобы результат не выводить на экран
        $cu = curl_init();
        $url_for_access_token = 'https://oauth.vk.com/access_token?client_id=7021425&client_secret=mAjolwdbKyq28GnGHchU&redirect_uri=http://todo2/site/login_vk&code='.$code;
        curl_setopt($cu,CURLOPT_URL, $url_for_access_token);
        curl_setopt($cu,CURLOPT_RETURNTRANSFER,TRUE);
        $access_token = curl_exec($cu);
        curl_close($cu);
        return $access_token;
    }

    public function getUser()
    {
        if ($this->getUserByVk_id()){
            return $this->getUserByVk_id();
        }
        else{
            return $this->getUserByEmail();
        }
    }

    public function getUserByEmail()
    {
        return User::findOne(['email'=>$this->email]);
    }

    public function getUserByVk_id()
    {
        return User::findOne(['vk_id'=>$this->user_id]);
    }
}
