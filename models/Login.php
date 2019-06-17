<?php

namespace app\models;

use yii\base\Model;

class Login extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email','password'],'required'],
            ['email','email'],
            ['password','validatePassword'],
            ['password','string','min'=>2,'max'=>20]
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if(!$user || !$user->validatePassword($this->password) || $user->status == 0)
            {
                $this->addError($attribute, 'Wrong email or password or account is not confirmed');
            }
        }
    }

    public  function getUser()
    {
        return User::findOne(['email'=>$this->email]);
    }

}