<?php
namespace App\Test\TestCase\Controller;

use App\Test\TestSuite\LoginTrait;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\LogoutController Test Case
 */
class LogoutControllerTest extends IntegrationTestCase
{
    use LoginTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
    ];

    /**
     * ログアウト処理のテスト
     *
     * @return void
     */
    public function testIndex()
    {
        $this->setLoginSession();
        $this->enableRetainFlashMessages();

        $this->get('/logout');

        $this->assertSession(
            'ログアウトしました',
            'Flash.flash.0.message',
            'ログアウト成功時のメッセージが正しくセットされていない'
        );

        // AppControllerのloadComponent('Auth')の設定に従う
        $logoutRedirect = [
            'controller' => 'Login',
            'action' => 'index',
        ];
        $this->assertRedirect(
            $logoutRedirect,
            'ログアウト成功時に適切にリダイレクトされていない'
        );
        $this->assertEmpty(
            $this->_requestSession->read('Auth'),
            'ログアウト処理が実行されていない'
        );
    }
}
