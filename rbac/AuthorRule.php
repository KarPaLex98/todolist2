<?php
namespace app\rbac;

use Yii;
use yii\helpers\VarDumper;
use yii\rbac\Item;
use yii\rbac\Rule;

class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
//        VarDumper::dump($params);
//        Yii::$app->end();
        return isset($params['Product']) ? $params['Product']->created_by === $user : false;
    }
}