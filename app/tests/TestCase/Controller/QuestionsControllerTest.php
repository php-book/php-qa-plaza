<?php

namespace App\Test\TestCase\Controller;

use App\Model\Entity\Answer;
use App\Model\Entity\Question;
use App\Model\Entity\User;
use App\Model\Table\QuestionsTable;
use App\Model\Table\UsersTable;
use App\Test\TestSuite\LoginTrait;
use Cake\ORM\ResultSet;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\QuestionsController Test Case
 *
 * @property QuestionsTable $Questions
 * @property UsersTable $Users
 */
class QuestionsControllerTest extends IntegrationTestCase
{
    use LoginTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Answers',
        'app.Questions',
        'app.Users',
    ];

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->Questions = TableRegistry::getTableLocator()->get('Questions');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @inheritdoc
     */
    public function tearDown()
    {
        unset($this->Questions);
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * 質問一覧画面のテスト
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/questions');

        $this->assertResponseOk('質問一覧画面が正常にレスポンスを返せていない');

        /** @var ResultSet $actual */
        $actual = $this->viewVariable('questions');
        // 代表の1件をとって、内容が期待したものになっているかを検査する
        $sampleQuestion = $actual->sample(1)->first();

        $this->assertInstanceOf(
            Question::class,
            $sampleQuestion,
            'ビュー変数に質問がセットされていない'
        );
        $this->assertInstanceOf(
            User::class,
            $sampleQuestion->user,
            '質問にユーザーが梱包されていない'
        );
        $this->assertInternalType(
            'integer',
            $sampleQuestion->answered_count,
            '質問に回答数が付いていない'
        );
    }

    /**
     * 質問詳細画面のテスト
     *
     * @return void
     */
    public function testView()
    {
        $targetQuestionId = 1;
        $this->get("/questions/view/{$targetQuestionId}");

        $this->assertResponseOk('質問詳細画面が正常にレスポンスを返せていない');

        $actualQuestion = $this->viewVariable('question');
        $this->assertInstanceOf(
            Question::class,
            $actualQuestion,
            '対象の質問がビュー変数にセットされていない'
        );
        $this->assertInstanceOf(
            User::class,
            $actualQuestion->user,
            '質問者情報がセットされていない'
        );
        $this->assertSame(
            $targetQuestionId,
            $actualQuestion->id,
            '指定した質問が取得されていない'
        );

        /** @var ResultSet $actualAnswers */
        $actualAnswers = $this->viewVariable('answers');
        $this->assertContainsOnlyInstancesOf(
            Answer::class,
            $actualAnswers->toList(),
            '回答一覧が正しくビュー変数にセットされていない'
        );
        $this->assertInstanceOf(
            User::class,
            $actualAnswers->sample(1)->first()->user,
            '回答者情報がセットされていない'
        );

        /** @var Answer $actualAnswer */
        $actualAnswer = $this->viewVariable('newAnswer');
        $this->assertInstanceOf(
            Answer::class,
            $actualAnswer,
            '回答情報が正しくセットされていない'
        );
    }

    /**
     * 質問詳細画面のテスト / 存在しない質問を表示しようとした時の確認
     *
     * @return void
     */
    public function testViewNotExists()
    {
        $targetQuestionId = 100;
        $this->get("/questions/view/{$targetQuestionId}");

        $this->assertResponseCode(404, '存在しない質問を削除しようとした時のレスポンスが正しくない');
    }

    /**
     * 質問投稿画面のテスト
     *
     * @return void
     */
    public function testAdd()
    {
        $this->readyToPost();

        $this->get('/questions/add');

        $this->assertResponseOk('質問投稿画面を開けていない');

        /** @var Question $actual */
        $actual = $this->viewVariable('question');
        $this->assertInstanceOf(
            Question::class,
            $actual,
            '質問のオブジェクトが正しくセットされていない'
        );
        $this->assertTrue(
            $actual->isNew(),
            'セットされている質問が新規データになっていない'
        );
    }

    /**
     * 質問投稿画面のテスト / 新規投稿時
     *
     * @return void
     */
    public function testAddPostSuccess()
    {
        $this->readyToPost();

        $postData = [
            'body' => '質問があります！'
        ];
        $this->post('/questions/add', $postData);

        $this->assertRedirect(
            ['controller' => 'Questions', 'action' => 'index'],
            '質問投稿完了時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '質問を投稿しました',
            'Flash.flash.0.message',
            '投稿成功時のメッセージが正しくセットされていない'
        );
    }

    /**
     * 質問投稿画面のテスト / 作成されるコンテンツの確認
     *
     * @return void
     */
    public function testAddCreateContent()
    {
        $this->readyToPost();

        $postData = [
            'body' => '質問があります！'
        ];
        $this->post('/questions/add', $postData);

        $actual = $this->Questions->find()->last();
        $this->assertSame(
            ['body' => $postData['body'], 'user_id' => $this->_session['Auth']['User']['id']],
            $actual->extract(['body', 'user_id']),
            '投稿した内容通りに質問が作成されていない'
        );
    }

    /**
     * 質問投稿画面の検査 / 投稿エラー時
     *
     * @return void
     */
    public function testAddPostError()
    {
        $this->readyToPost();

        $this->post('/questions/add', []);

        $this->assertResponseOk('成功のレスポンスが返っていない');
        $this->assertSession(
            '質問の投稿に失敗しました',
            'Flash.flash.0.message',
            '投稿失敗時のメッセージが正しくセットされていない'
        );
    }

    /**
     * 質問削除のテスト / 削除成功時の確認
     *
     * @return void
     */
    public function testDeleteSuccess()
    {
        $this->readyToPost();

        $targetQuestionId = 1;
        $this->post("/questions/delete/{$targetQuestionId}");

        $this->assertRedirect(
            ['controller' => 'Questions', 'action' => 'index'],
            '質問削除完了時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '質問を削除しました',
            'Flash.flash.0.message',
            '削除成功時のメッセージが正しくセットされていない'
        );
    }

    /**
     * 質問削除のテスト / 削除されるコンテンツの確認
     *
     * @return void
     */
    public function testDeleteContent()
    {
        $this->readyToPost();

        $targetQuestionId = 1;
        $this->post("/questions/delete/{$targetQuestionId}");

        $this->assertFalse(
            $this->Questions->exists(['id' => $targetQuestionId]),
            '削除対象の質問が削除されていない'
        );
    }

    /**
     * 質問削除のテスト / 存在しない質問を削除しようとした時の確認
     *
     * @return void
     */
    public function testDeleteNotExists()
    {
        $this->readyToPost();

        $targetQuestionId = 100;
        $this->post("/questions/delete/{$targetQuestionId}");

        $this->assertResponseCode(404, '存在しない質問を削除しようとした時のレスポンスが正しくない');
    }

    /**
     * 質問削除のテスト / 他のユーザーが質問を削除しようとした時の確認
     *
     * @return void
     */
    public function testDeleteOtherUser()
    {
        $this->readyToPost();

        $targetQuestionId = 2; // 他のユーザーの質問
        $this->post("/questions/delete/{$targetQuestionId}");

        $this->assertRedirect(
            ['controller' => 'Questions', 'action' => 'index'],
            '他のユーザーが質問を削除しようとした時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '他のユーザーの質問を削除することは出来ません',
            'Flash.flash.0.message',
            '他のユーザーが質問を削除しようとした時のメッセージが正しくセットされていない'
        );
    }
}
