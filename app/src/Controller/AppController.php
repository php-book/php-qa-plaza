<?php

namespace App\Controller;

use Cake\Controller\Controller;

/**
 * Application Controller
 */
class AppController extends Controller
{
    /**
     * 初期化処理
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Login',
                'action' => 'index'
            ],
            'loginRedirect' => [
                'controller' => 'Questions',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Login',
                'action' => 'index'
            ],
            'unauthorizedRedirect' => [
                'controller' => 'Login',
                'action' => 'index'
            ],
            'authError' => 'ログインが必要です'
        ]);

        $this->Auth->allow(['display', 'index', 'view']);
    }
}
