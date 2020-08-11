<?php

namespace app\rbac;

use yii\helpers\VarDumper;
use yii\rbac\Item;
use yii\rbac\Rule;

class AdvancedUserRule extends Rule
{
    public $name = 'isPavel';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
//        VarDumper::dump($user, 10, true);
//        VarDumper::dump($params, 10, true);
//        VarDumper::dump($item, 10, true);
//        \Yii::$app->end();
        return $user == 2;
    }
}