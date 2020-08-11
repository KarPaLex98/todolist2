<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $nestedSets = $auth->createPermission('nestedSets');
        $nestedSets->description = 'actions with NestedSets';
        $auth->add($nestedSets);

        $EAV = $auth->createPermission('EAV');
        $EAV->description = 'Actions with EAV';
        $auth->add($EAV);

        $toDO = $auth->createPermission('toDo');
        $toDO->description = 'Actions with toDo list';
        $auth->add($toDO);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $toDO);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $EAV);
        $auth->addChild($admin, $nestedSets);
        $auth->addChild($admin, $user);

        $auth->assign($user, 2);
//        $auth->assign($admin, 1);
    }
}