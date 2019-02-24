<?php
namespace App\Test\TestCase\Controller;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\TestSuite\LoginTrait;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @property UsersTable $Users
 */
class UsersControllerTest extends IntegrationTestCase
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
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * ユーザー登録画面のテスト
     *
     * @return void
     */
    public function testAdd()
    {
        $this->get('/users/add');

        $this->assertResponseOk('ユーザー登録画面を開けていない');

        /** @var User $actual */
        $actual = $this->viewVariable('user');
        $this->assertInstanceOf(
            User::class,
            $actual,
            'ユーザーのオブジェクトが正しくセットされていない'
        );
        $this->assertTrue(
            $actual->isNew(),
            '新規ユーザーがセットされていない'
        );
    }

    /**
     * ユーザー登録画面のテスト / 登録成功時の確認
     *
     * @return void
     */
    public function testAddPostSuccess()
    {
        $data = [
            'username' => 'iamnew',
            'password' => 'nicepassword2019',
            'password_confirm' => 'nicepassword2019',
            'nickname' => 'IamNew',
        ];

        $this->enableCsrfToken();
        $this->post('/users/add', $data);

        $this->assertRedirect(
            ['controller' => 'Login', 'action' => 'index'],
            'ユーザー登録時にログイン画面にリダイレクトがかかっていない'
        );

        $newUserExists = $this->Users->exists([
            'username' => $data['username'],
        ]);
        $this->assertTrue(
            $newUserExists,
            '新規ユーザーが正しく保存されていない'
        );
    }

    /**
     * ユーザー登録画面のテスト / 登録失敗時の確認
     *
     * @return void
     */
    public function testAddPostError()
    {
        $data = [
            'username' => 'iamnew',
            'password' => 'nicepassword2019',
            // 'password_confirm' => 必須フィールド未入力
            'nickname' => 'IamNew',
        ];

        $this->enableCsrfToken();
        $this->enableRetainFlashMessages();
        $this->post('/users/add', $data);

        $this->assertResponseOk('ユーザー登録失敗時に200応答ができていない');

        $this->assertSession(
            'ユーザーの登録に失敗しました',
            'Flash.flash.0.message',
            'ユーザー登録失敗時のメッセージが正しくセットされていない'
        );

        /** @var User $actual */
        $actual = $this->viewVariable('user');
        $this->assertInstanceOf(
            User::class,
            $actual,
            'ユーザーのオブジェクトが正しくセットされていない'
        );
        $this->assertTrue(
            $actual->isNew(),
            '新規ユーザーがセットされていない'
        );
    }

    /**
     * 編集画面のテスト
     *
     * @return void
     */
    public function testEdit()
    {
        $this->readyToPost();
        $this->get('/users/edit');

        $this->assertResponseOk('ユーザー編集画面に入れていない');

        $actual = $this->viewVariable('user');
        $this->assertSame(
            $this->_session['Auth']['User']['id'],
            $actual->id,
            'ログイン中のユーザーの情報を取得できていない'
        );
    }

    /**
     * 編集画面のテスト / 編集成功時の確認
     *
     * @return void
     */
    public function testEditPutSuccess()
    {
        $this->readyToPost();

        $beforeUser = $this->Users->get($this->_session['Auth']['User']['id']);
        $data = [
            'username' => $beforeUser->name . 'Edited',
            'nickname' => $beforeUser->nickname . 'Edited'
        ];

        $this->put('/users/edit', $data);

        $this->assertSession(
            'ユーザー情報を更新しました',
            'Flash.flash.0.message',
            'ユーザー情報更新成功時のメッセージが正しくセットされていない'
        );

        $afterUser = $this->Users->get($this->_session['Auth']['User']['id']);
        $this->assertSame(
            $afterUser->extract(['username', 'nickname']),
            $data,
            'ユーザー情報が正しく更新されていない'
        );
        $this->assertEquals(
            $afterUser->toArray(),
            $this->_requestSession->read('Auth')['User'],
            'ユーザー情報更新時にログイン状態が更新されていない'
        );
    }

    /**
     * 編集画面のテスト / 編集失敗時の確認
     *
     * @return void
     */
    public function testEditPutError()
    {
        $this->readyToPost();

        $beforeUser = $this->Users->get($this->_session['Auth']['User']['id']);
        $invalidUsername = str_pad($beforeUser->username, 100, '*');
        $data = ['username' => $invalidUsername];

        $this->put('/users/edit', $data);

        $this->assertSession(
            'ユーザー情報の更新に失敗しました',
            'Flash.flash.0.message',
            'ユーザー情報更新成功時のメッセージが正しくセットされていない'
        );

        $userNotEdited = $this->Users->exists($beforeUser->toArray());
        $this->assertTrue(
            $userNotEdited,
            'ユーザー情報の更新を防げていない'
        );
    }

    /**
     * パスワード更新画面のテスト
     *
     * @return void
     */
    public function testPassword()
    {
        $this->readyToPost();
        $this->get('/users/password');

        $this->assertResponseOk('パスワード編集画面に入れていない');

        $actual = $this->viewVariable('user');
        $this->assertInstanceOf(
            User::class,
            $actual,
            'ユーザーエンティティがセットされていない'
        );
    }

    /**
     * パスワード更新画面のテスト / 更新成功時の確認
     *
     * @return void
     */
    public function testPasswordPostSuccess()
    {
        $data = [
            'password_current' => 'password1',
            'password' => 'newpassword100',
            'password_confirm' => 'newpassword100'
        ];

        $this->readyToPost();
        $this->post('/users/password', $data);

        $this->assertRedirect(
            ['action' => 'edit'],
            'パスワード更新成功時に正しくリダイレクトされていない'
        );
        $this->assertSession(
            'パスワードを更新しました',
            'Flash.flash.0.message',
            'パスワード更新成功時のメッセージが正しくセットされていない'
        );
        $actual = $this->Users->get($this->_session['Auth']['User']['id']);
        $this->assertTrue(
            (new DefaultPasswordHasher())->check($data['password'], $actual->password),
            'パスワードが正しく更新されていない'
        );
        $this->assertEquals(
            $actual->toArray(),
            $this->_requestSession->read('Auth')['User'],
            'パスワード更新時にログイン状態が更新されていない'
        );
    }

    /**
     * パスワード更新画面のテスト / 更新失敗時の確認
     *
     * @return void
     */
    public function testPasswordPostError()
    {
        $data = [
            'password_current' => 'invalidPassword',
            'password' => 'newpassword100',
            'password_confirm' => 'newpassword100'
        ];

        $this->readyToPost();
        $beforeUser = $this->Users->get($this->_session['Auth']['User']['id']);

        $this->post('/users/password', $data);

        $this->assertResponseOk(
            'パスワード更新失敗時に200応答ができていない'
        );
        $this->assertSession(
            'パスワードの更新に失敗しました',
            'Flash.flash.0.message',
            'パスワード更新成功時のメッセージが正しくセットされていない'
        );
        $userNotEdited = $this->Users->exists($beforeUser->toArray());
        $this->assertTrue(
            $userNotEdited,
            'ユーザー情報の更新を防げていない'
        );
    }
}
