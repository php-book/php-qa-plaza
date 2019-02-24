<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QuestionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QuestionsTable Test Case
 */
class QuestionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\QuestionsTable
     */
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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Questions') ? [] : ['className' => QuestionsTable::class];
        $this->Questions = TableRegistry::getTableLocator()->get('Questions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Questions);

        parent::tearDown();
    }

    /**
     * findQuestionsWithAnsweredCount()のテスト
     *
     * @return void
     */
    public function testFindQuestionsWithAnsweredCount()
    {
        $actual = $this->Questions->findQuestionsWithAnsweredCount();

        $this->assertTrue(
            $actual->isAutoFieldsEnabled(),
            'auto fieldが有効になっていない'
        );

        $expected = [1 => 2, 2 => 0];
        $this->assertSame(
            $expected,
            $actual->combine('id', 'answered_count')->toArray(),
            '回答数を正しく取得できていない'
        );
    }
}
