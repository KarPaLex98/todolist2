<?php

namespace app\controllers;

use Yii;
use app\models\Join;
use app\models\Login;
use app\models\Todo;
use app\models\Login_vk;
use yii\helpers\Html;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionJoin()
    {
        if (Yii::$app->user->isGuest) {
            $model_join = new Join();

            if (isset($_POST['Join'])) {
                $model_join->attributes = Yii::$app->request->post('Join');
                if ($model_join->validate() && $model_join->signup()) {
                    Yii::$app->session->setFlash('success', "An email has been sent to your email to confirm your account.");
                }
            }
            return $this->render('join', ['model' => $model_join]);
        } else {
            return $this->redirect(['site/todo']);
        }
    }

    public function actionActivation()
    {
        if (Yii::$app->user->isGuest) {
//            Берем из URL email_confirm_token, а затем сверяем с таким же из БД для данного пользователя.
//            Если совпадает, то меняем статус пользователя и авторизовываем, иначе отправляем на главную.
            $token = Html::encode(Yii::$app->request->get('token'));
            $model_join = new Join();
            $model_login = $model_join->getUserByToken($token);
            if ($model_join->confirm($token)) {
                Yii::$app->user->login($model_login);
            }
            return $this->redirect(['site/todo']);
        } else return $this->redirect(['site/todo']);
    }

    public function actionLogin()
    {
        if (Yii::$app->user->isGuest){
            $model_login = new Login();

            if(Yii::$app->request->post('Login'))
            {
                $model_login->attributes = Yii::$app->request->post('Login');
                if($model_login->validate())
                {
                    Yii::$app->user->login($model_login->getUser());
                    return $this->redirect(['site/todo']);
                }
            }
            return $this->render('login', ['model' => $model_login]);
        }
        else return $this->redirect(['site/todo']);
    }

    public function actionLogin_vk(){
        $model_login_vk = new Login_vk();

        if(!Yii::$app->request->get('code')) {
//            Отправляем запрос на авторизацию
            return $this->redirect('https://oauth.vk.com/authorize?client_id=7023487&display=page&redirect_uri=http://'.Yii::$app->getRequest()->serverName.'/site/login_vk&scope=email,offline&response_type=code&v=5.95');
        }
        elseif(Yii::$app->request->get('code')) {
//            Получаем access_token и парсим из него данные
            $access_token = $model_login_vk->getAccessToken($_GET['code']);
            $ob = json_decode($access_token);

            if($ob->access_token) {
//                Получаем id и email, если он привязан к аккаунту в VK (в противном случае оставляем пустым) и регистрируем новый аккаунт с этими данными.
//                Затем авторизовываем нового пользователя.
                $model_login_vk->user_id = $ob->user_id;
                $model_login_vk->email = $ob->email? $ob->email : '';
                if ($model_login_vk->signup_vk()){
                    Yii::$app->user->login($model_login_vk->getUser());
                    return $this->redirect(['site/todo']);
                }
            }
            elseif($ob->error) {
//                При возникновении ошибки отправляем на исходную страницу.
                return $this->redirect(['site/login']);
            }
        }
        elseif($_GET['error']) {
//            При возникновении ошибки отправляем на исходную страницу.
            return $this->redirect(['site/login']);
        }
    }

    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest)
        {
            Yii::$app->user->logout();
            return $this->redirect(['site/login']);
        }
    }

    public function actionTodo()
    {
        if (!Yii::$app->user->isGuest) {
            $todo_list = Todo::getAll();
            $model_todo = new Todo();
            if (Yii::$app->request->post('Create')){
                $model_todo->title = $_POST['Todo']['title'];
                $model_todo->description = $_POST['Todo']['description'];
                $model_todo->id_user = Yii::$app->user->getId();
                $model_todo->is_completed = false;
                $model_todo->is_Send = false;
                $model_todo->date_time = $_POST['Todo']['date_time'];
                if ($model_todo->validate() && $model_todo->save()) {
                    return $this->redirect(['site/todo']);
                }
            }
            return $this->render('todo', ['model2' => $todo_list, 'model' => $model_todo]);
        }
    else return $this->redirect(['site/index']);
}

    public function actionDelete($id){
        $model_delete = Todo::getOne($id);

        $model_delete->delete();
        return $this->redirect(['site/todo']);
    }

    public function actionCompleted($id){
        $model_completed = Todo::getOne($id);

        $model_completed->is_completed = !$model_completed->is_completed;
        if ($model_completed->validate() && $model_completed->save())
        {
            return $this->redirect(['site/todo']);
        }
    }

    public function actionEdit($id){
        $model_edit = Todo::getOne($id);

        if (Yii::$app->request->post('Edit'))
        {
            $model_edit->title = $_POST['Todo']['title'];
            $model_edit->description = $_POST['Todo']['description'];
            $model_edit->date_time = $_POST['Todo']['date_time'];
            if ($model_edit->validate() && $model_edit->save())
            {
                return $this->redirect(['site/todo']);
            }
        }
        return $this->render('edit', ['model'=>$model_edit]);
    }

}
