<?php

namespace App\Test\TestSuite;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * IntegrationTestにおいて、
 * ユーザーをログイン状態にした状態をセットする
 *
 * @package App\Test\TestSuite
 * @mixin IntegrationTestCase
 */
trait LoginTrait
{

    /**
     * リクエスト発行時にログイン状態を成立させる
     *
     * @return void
     */
    protected function setLoginSession()
    {
        $Users = TableRegistry::getTableLocator()->get('Users');
        $auth = ['Auth' => ['User' => $Users->find()->first()]];
        $this->session($auth);
    }

    /**
     * 通常のpostリクエストを行うための前提条件を整える
     *
     * @return void
     */
    protected function readyToPost()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();
        $this->setLoginSession();
    }
}
