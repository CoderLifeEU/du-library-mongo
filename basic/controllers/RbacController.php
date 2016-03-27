<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // add "createPost" permission
        $createPost = $auth->createPermission('reserveBooks');
        $createPost->description = 'Reserve Books';
        $auth->add($createPost);

        // add "updatePost" permission
        $updatePost = $auth->createPermission('manageBooks');
        $updatePost->description = 'Manage Books';
        $auth->add($updatePost);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('user');
        $auth->add($author);
        $auth->addChild($author, $createPost);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $author);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($author, new \MongoId("56d1456e1579f65801000029"));
        $auth->assign($admin, new \MongoId("56c045ae42fa52cf1b6e7481"));
    }
}