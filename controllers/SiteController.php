<?php

namespace app\controllers;

use Yii;
use app\models\Join;
use app\models\Login;
use app\models\Todo;
use app\models\Login_vk;
use app\models\Menu;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['activation'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['activation'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-value'],
                        'roles' => ['toDo'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete-value'],
                        'roles' => ['toDo'],
                    ],
                ],
            ],
        ];
    }

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
        if (Yii::$app->user->isGuest) {
            $model_login = new Login();

            if (Yii::$app->request->post('Login')) {
                $model_login->attributes = Yii::$app->request->post('Login');
                if ($model_login->validate()) {
                    Yii::$app->user->login($model_login->getUser());
                    return $this->redirect(['site/todo']);
                }
            }
            return $this->render('login', ['model' => $model_login]);
        } else return $this->redirect(['site/todo']);
    }

    public function actionFriends()
    {
        $cu = curl_init();
        $url = 'https://api.vk.com/method/friends.getOnline?access_token=' . $_SESSION['token'] . '&v=5.95';
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, TRUE);
        $friends_online = curl_exec($cu);
        curl_close($cu);

        $tmp = json_decode($friends_online);

        $ids_string_from_JSON = implode(",", $tmp->response);

        $cu = curl_init();
        $url = 'https://api.vk.com/method/users.get?user_ids=' . $ids_string_from_JSON . '&fields=photo_100&access_token=' . $_SESSION['token'] . '&v=5.95';
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, TRUE);
        $friends = curl_exec($cu);
        curl_close($cu);

        $tmp = json_decode($friends);
        return $this->render('friends', ['friends' => $tmp->response]);
    }

    public function actionGroups(){
        $cu = curl_init();
        $url = 'https://api.vk.com/method/groups.get?user_id='.Yii::$app->request->get('id').'access_token=' . $_SESSION['token'] . '&v=5.95';
        echo '<pre>';
        var_dump($url);
        echo '</pre>';
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, TRUE);
        $groups = curl_exec($cu);
        echo '<pre>';
        var_dump($groups);
        echo '</pre>';
        curl_close($cu);

        $tmp = json_decode($groups);

        return $this->render('groups', ['groups' => $tmp->response]);
    }

    public function actionLogin_vk()
    {
        $model_login_vk = new Login_vk();

        if (!Yii::$app->request->get('code')) {
//            Отправляем запрос на авторизацию
            return $this->redirect('https://oauth.vk.com/authorize?client_id=7023487&display=page&redirect_uri=http://' . Yii::$app->getRequest()->serverName . '/site/login_vk&scope=email,offline,friends&response_type=code&v=5.95');
        } elseif (Yii::$app->request->get('code')) {
//            Получаем access_token и парсим из него данные
            $access_token = $model_login_vk->getAccessToken($_GET['code']);
            $ob = json_decode($access_token);
//            var_dump($ob);
//            VarDumper::dump($od,10,true);
//            Yii::$app->end();
            if ($ob->access_token) {
//                Получаем id и email, если он привязан к аккаунту в VK (в противном случае оставляем пустым) и регистрируем новый аккаунт с этими данными.
//                Затем авторизовываем нового пользователя.
                session_start();
                $_SESSION['token'] = $ob->access_token;
                session_write_close();

                $model_login_vk->user_id = $ob->user_id;
                $model_login_vk->email = $ob->email ? $ob->email : '';
                if ($model_login_vk->signup_vk()) {
                    Yii::$app->user->login($model_login_vk->getUser());
                    return $this->redirect(['site/todo']);
                }
            } elseif ($ob->error) {
//                При возникновении ошибки отправляем на исходную страницу.
                return $this->redirect(['site/login']);
            }
        } elseif ($_GET['error']) {
//            При возникновении ошибки отправляем на исходную страницу.
            return $this->redirect(['site/login']);
        }
    }

    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->redirect(['site/login']);
        }
    }

    public function actionTodo()
    {
        if (!Yii::$app->user->isGuest) {
            $todo_list = Todo::getAll();
            $model_todo = new Todo();
            if (Yii::$app->request->post('Create')) {
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
        } else return $this->redirect(['site/index']);
    }

    public function actionDelete($id)
    {
        $model_delete = Todo::getOne($id);

        $model_delete->delete();
        return $this->redirect(['site/todo']);
    }

    public function actionCompleted($id)
    {
        $model_completed = Todo::getOne($id);

        $model_completed->is_completed = !$model_completed->is_completed;
        if ($model_completed->validate() && $model_completed->save()) {
            return $this->redirect(['site/todo']);
        }
    }

    public function actionEdit($id)
    {
        $model_edit = Todo::getOne($id);

        if (Yii::$app->request->post('Edit')) {
            $model_edit->title = $_POST['Todo']['title'];
            $model_edit->description = $_POST['Todo']['description'];
            $model_edit->date_time = $_POST['Todo']['date_time'];
            if ($model_edit->validate() && $model_edit->save()) {
                return $this->redirect(['site/todo']);
            }
        }
        return $this->render('edit', ['model' => $model_edit]);
    }


}
