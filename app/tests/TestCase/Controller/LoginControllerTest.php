<?php

namespace App\Test\TestCase\Controller;

use App\Test\TestSuite\LoginTrait;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\LoginController Test Case
 */
class LoginControllerTest extends IntegrationTestCase
{
    use LoginTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

    /**
     * ログイン画面のテスト
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/login');

        $this->assertResponseOk('未ログイン状態でログイン画面が表示できていない');
    }

    /**
     * ログイン処理のテスト / ログインに成功した場合
     *
     * @return void
     */
    public function testIndexPostSuccess()
    {
        $this->enableCsrfToken();

        $data = [
            'username' => 'itosho',
            'password' => 'password1',
        ];
        $this->post('/login', $data);

        // AppControllerのloadComponent('Auth')の設定に従う
        $logoutRedirect = [
            'controller' => 'Questions',
            'action' => 'index',
        ];
        $this->assertRedirect(
            $logoutRedirect,
            'ログイン成功時に適切にリダイレクトされていない'
        );
    }

    /**
     * ログイン画面のテスト / ログイン済みの場合
     *
     * @return void
     */
    public function testIndexLoggedIn()
    {
        $this->setLoginSession();

        $this->get('/login');

        // AppControllerのloadComponent('Auth')の設定に従う
        $loginRedirect = [
            'controller' => 'Questions',
            'action' => 'index',
        ];
        $this->assertRedirect(
            $loginRedirect,
            'ログイン済みでログイン画面に遷移した時に適切にリダイレクトされていない'
        );
    }

    /**
     * ログイン処理のテスト / ログインに失敗した場合
     *
     * @return void
     */
    public function testIndexPostError()
    {
        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();

        $data = [
            'username' => 'itosho',
            'password' => 'invalidpassword',
        ];
        $this->post('/login', $data);

        $this->assertResponseOk('ログイン失敗時に200応答ができていない');

        $this->assertSession(
            'ユーザー名またはパスワードが不正です',
            'Flash.flash.0.message',
            'ログイン失敗時のメッセージが正しくセットされていない'
        );
    }
}
