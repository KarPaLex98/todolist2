<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();

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

        $auth = Yii::$app->authManager;


        $rule = new \app\rbac\AdvancedUserRule();
        $auth->add($rule);


        $watchAdminsPages = $auth->createPermission('watchAdminsPages');
        $watchAdminsPages->description = "Watch admin's pages";
        $watchAdminsPages->ruleName = $rule->name;
        $auth->add($watchAdminsPages);

        $auth->addChild($toDO, $watchAdminsPages);

        $auth->addChild($user, $watchAdminsPages);


        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $EAV);
        $auth->addChild($admin, $nestedSets);
        $auth->addChild($admin, $user);

        $auth->assign($user, 2);
    }
}