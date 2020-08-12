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

        $addProduct = $auth->createPermission('addProduct');
        $addProduct->description = 'Add new product';
        $auth->add($addProduct);

        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Delete product';
        $auth->add($deleteProduct);

        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Update product';
        $auth->add($updateProduct);

        $watchProducts = $auth->createPermission('watchProducts');
        $watchProducts->description = 'Watch products';
        $auth->add($watchProducts);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $watchProducts);
        $auth->addChild($user, $addProduct);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $addProduct);
        $auth->addChild($admin, $deleteProduct);
        $auth->addChild($admin, $updateProduct);
        $auth->addChild($admin, $user);

        $rule = new \app\rbac\AuthorRule();
        $auth->add($rule);

        $updateOwnProduct = $auth->createPermission('updateOwnProduct');
        $updateOwnProduct->description = 'Update own product';
        $updateOwnProduct->ruleName = $rule->name;
        $auth->add($updateOwnProduct);

        $auth->addChild($updateOwnProduct, $updateProduct);
        $auth->addChild($user, $updateOwnProduct);

        $auth->assign($user, 12);
    }
}