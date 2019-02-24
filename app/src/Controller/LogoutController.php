<?php

namespace App\Controller;

/**
 * Logout Controller
 */
class LogoutController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny(['index']);
    }

    /**
     * ログアウト処理
     *
     * @return \Cake\Http\Response|null ログアウト後にログインTOPに遷移する
     */
    public function index()
    {
        $this->Flash->success('ログアウトしました');

        return $this->redirect($this->Auth->logout());
    }
}
