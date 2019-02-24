<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AnswersController;
use App\Model\Entity\Answer;
use App\Model\Entity\Question;
use App\Model\Table\AnswersTable;
use App\Model\Table\QuestionsTable;
use App\Test\TestSuite\LoginTrait;
use Cake\Chronos\Chronos;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\AnswersController Test Case
 *
 * @property AnswersTable $Answers
 * @property QuestionsTable $Questions
 */
class AnswersControllerTest extends IntegrationTestCase
{
    use LoginTrait;

    /** @var AnswersTable */
    public $Answers;

    /** @var  */
    public $Questions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.answers',
        'app.questions',
        'app.users',
    ];

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->Answers = TableRegistry::getTableLocator()->get('Answers');
        $this->Questions = TableRegistry::getTableLocator()->get('Questions');
    }

    /**
     * @inheritDoc
     */
    public function tearDown()
    {
        unset($this->Answers);
        unset($this->Questions);

        parent::tearDown();
    }

    /**
     * 回答投稿のテスト
     *
     * @return void
     */
    public function testAdd()
    {
        $question = $this->Questions->find()->first();
        $targetQuestionId = $question->id;

        $data = [
            'question_id' => $targetQuestionId,
            'body' => '私、わかりますよ！',
        ];

        $this->readyToPost();

        $this->post('/answers/add', $data);

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $targetQuestionId,
            ],
            '回答投稿完了時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '回答を投稿しました',
            'Flash.flash.0.message',
            '回答投稿成功時のメッセージが正しくセットされていない'
        );
    }

    /**
     * 回答投稿のテスト / データが作成されているかの確認
     *
     * @return void
     */
    public function testAddCreateContent()
    {
        $beforeAnswerCount = $this->Answers->find()->count();
        $question = $this->Questions->find()->first();
        $targetQuestionId = $question->id;

        $data = [
            'question_id' => $targetQuestionId,
            'body' => '私、わかりますよ！',
        ];

        $this->readyToPost();

        $this->post('/answers/add', $data);

        $this->assertSame(
            $beforeAnswerCount + 1,
            $this->Answers->find()->count(),
            '回答が新規に保存されていない'
        );
        /** @var Answer $actual */
        $actual = $this->Answers->find()->last();
        $this->assertSame(
            $actual->extract(['question_id', 'body']),
            $data,
            '投稿した内容が正しくセットされていない'
        );
        $this->assertSame(
            $this->_session['Auth']['User']['id'],
            $actual->user_id,
            '投稿者情報が正しくセットされていない'
        );
    }

    /**
     * 回答投稿のテスト / 失敗データの確認
     *
     * @return void
     */
    public function testAddError()
    {
        $beforeAnswerCount = $this->Answers->find()->count();
        $question = $this->Questions->find()->first();

        $data = [
            'question_id' => $question->id,
            // 'body'  #bodyを未入力のままにする
        ];

        $this->readyToPost();

        $this->post('/answers/add', $data);

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $question->id,
            ],
            '回答投稿失敗時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '回答の投稿に失敗しました',
            'Flash.flash.0.message',
            '回答投稿成功時のメッセージが正しくセットされていない'
        );

        $this->assertSame(
            $beforeAnswerCount,
            $this->Answers->find()->count(),
            '回答の新規保存を防げていない'
        );
    }

    /**
     * 回答投稿のテスト / 回答の上限数を超過している場合
     *
     * @return void
     */
    public function testAddErrorExceededLimit()
    {
        /** @var Question $question */
        $question = $this->Questions->find()->first();
        $this->setupAnswersToUpperLimit($question);
        $targetQuestionId = $question->id;

        $data = [
            'question_id' => $targetQuestionId,
            'body' => '私、わかりますよ！',
        ];

        $this->readyToPost();

        $this->post('/answers/add', $data);

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $question->id,
            ],
            '回答上限数到達時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '回答の上限数に達しました',
            'Flash.flash.0.message',
            '回答投稿成功時のメッセージが正しくセットされていない'
        );

        $this->assertSame(
            AnswersController::ANSWER_UPPER_LIMIT,
            $this->Answers->find()->count(),
            '回答の新規保存を防げていない'
        );
    }

    /**
     * 回答投稿のテスト / 対象の質問が存在しない場合
     *
     * @return void
     */
    public function testAddErrorQuestionNotExits()
    {
        $beforeAnswerCount = $this->Answers->find()->count();
        $latestQuestion = $this->Questions->find()->last();
        $targetQuestionId = $latestQuestion->id + 1;

        $data = [
            'question_id' => $targetQuestionId,
            'body' => '私、知っています！'
        ];

        $this->readyToPost();

        $this->post('/answers/add', $data);

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $targetQuestionId,
            ],
            '回答投稿失敗時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '回答の投稿に失敗しました',
            'Flash.flash.0.message',
            '回答投稿成功時のメッセージが正しくセットされていない'
        );

        $this->assertSame(
            $beforeAnswerCount,
            $this->Answers->find()->count(),
            '回答の新規保存を防げていない'
        );
    }

    /**
     * 上限数の分だけ回答をセットし、事前条件を整える
     *
     * @param Question $targetQuestion
     * @return void
     */
    private function setupAnswersToUpperLimit($targetQuestion)
    {
        $existsCount = $this->Answers->find()
            ->where(['question_id' => $targetQuestion->id])
            ->count();

        $query = $this->Answers->query();
        $query->insert(['question_id', 'user_id', 'body', 'created', 'modified']);
        $now = Chronos::now();
        for ($i = $existsCount + 1; $i <= AnswersController::ANSWER_UPPER_LIMIT; $i++) {
            $query->values([
                'question_id' => $targetQuestion->id,
                'user_id' => $targetQuestion->user_id,
                'body' => "{$i}個目の回答です",
                'created' => $now,
                'modified' => $now,
            ]);
        }

        $query->execute();
    }

    /**
     * 回答削除のテスト
     *
     * @return void
     */
    public function testDelete()
    {
        $this->readyToPost();

        /** @var Answer $targetAnswer */
        $targetAnswer = $this->Answers->find()
            ->where(['user_id' => $this->_session['Auth']['User']['id']])
            ->first();

        $this->post("/answers/delete/{$targetAnswer->id}");

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $targetAnswer->question_id,
            ],
            '回答削除完了時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '回答を削除しました',
            'Flash.flash.0.message',
            '回答投削除功時のメッセージが正しくセットされていない'
        );

        $targetAnswer = $this->Answers->find()
            ->where(['id' => $targetAnswer->id]);
        $this->assertTrue(
            $targetAnswer->isEmpty(),
            '回答が削除されていない'
        );
    }

    /**
     * 回答削除のテスト / 他所の回答を指定した場合
     *
     * @return void
     */
    public function testDeleteNotOwnedAnswer()
    {
        $beforeAnswerCount = $this->Answers->find()->count();

        $this->readyToPost();

        /** @var Answer $targetAnswer */
        $targetAnswer = $this->Answers->find()
            ->where(['user_id !=' => $this->_session['Auth']['User']['id']])
            ->first();

        $this->post("/answers/delete/{$targetAnswer->id}");

        $this->assertRedirect(
            [
                'controller' => 'Questions',
                'action' => 'view',
                $targetAnswer->question_id,
            ],
            '回答削除失敗時にリダイレクトが正しくかかっていない'
        );
        $this->assertSession(
            '他のユーザーの回答を削除することはできません',
            'Flash.flash.0.message',
            '自分以外の回答投削施行時のメッセージが正しくセットされていない'
        );

        $this->assertSame(
            $beforeAnswerCount,
            $this->Answers->find()->count(),
            '回答の新規保存を防げていない'
        );
    }

    /**
     * 回答削除のテスト / 対象の回答が存在しない場合
     *
     * @return void
     */
    public function testDeleteNotExists()
    {
        $targetAnswerId = $this->Answers->find()->last()->id + 1;

        $this->readyToPost();

        $this->post("/answers/delete/{$targetAnswerId}");

        $this->assertResponseCode(
            404,
            '削除対象の回答が見つからないときに正しく返答できていない'
        );
    }
}
