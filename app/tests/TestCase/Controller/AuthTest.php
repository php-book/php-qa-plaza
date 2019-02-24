<?php

namespace App\Test\TestCase\Controller;

use App\Controller\AppController;
use Cake\Http\Response;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\IntegrationTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class AuthTest
 *
 * AppControllerの持つ設定部分のうち、AuthComponentの認可に関連する内容を個別にテストを行う
 * @package App\Test\TestCase\Controller
 */
class AuthTest extends IntegrationTestCase
{
    /** @var AppController test subject */
    public $Controller;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        /** @var AppController & MockObject $Controller */
        $Controller = $this->createPartialMock(AppController::class, ['isAction']);
        $Controller->method('isAction')
            ->willReturn(true);

        $Controller->setRequest(ServerRequestFactory::fromGlobals())
            ->setResponse(new Response());
        $this->Controller = $Controller;

        $this->Controller->initialize();
    }

    /**
     * 非ログイン状態でのアクセスが許可されているアクションのテスト
     *
     * @dataProvider allowedActionProvider
     *
     * @return void
     */
    public function testAllow(string $action)
    {
        $this->Controller->setRequest(
            $this->Controller->getRequest()
                ->withParam('action', $action)
        );

        // 異常がなければ戻り値がnullになる
        $response = $this->Controller->startupProcess();

        $this->assertNull(
            $response,
            '非ログインアクセスが許可されているアクションに200応答ができていない'
        );
    }

    /**
     * 非ログインアクセスが許可されるアクション名を提供する
     *
     * @return array[] アクション名を0番目に持つ配列の配列
     */
    public function allowedActionProvider()
    {
        return [
            ['display'],
            ['index'],
            ['view'],
        ];
    }

    /**
     * 非ログイン状態でのアクセスが許可されていないアクションのテスト
     *
     * @return void
     */
    public function testDeny()
    {
        $this->Controller->setRequest(
            $this->Controller->getRequest()
                ->withParam('action', 'add')
        );

        // 異常があればResponseオブジェクトが戻される
        $response = $this->Controller->startupProcess();

        $this->assertSame(
            '/login',
            $response->getHeaderLine('Location'),
            '非ログインアクセスが許可されているアクションを拒否できていない'
        );
    }
}
