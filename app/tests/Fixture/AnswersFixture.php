<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AnswersFixture
 */
class AnswersFixture extends TestFixture
{
    /**
     * テーブルのフィールド情報
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'question_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'body' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'question_id' => 1,
                'user_id' => 1,
                'body' => 'ちなみに僕はCakePHPが好きです！',
                'created' => '2018-12-01 13:30:00',
                'modified' => '2018-12-01 13:30:00',
            ],
            [
                'id' => 2,
                'question_id' => 1,
                'user_id' => 2,
                'body' => '私はLaravelをよく使います。',
                'created' => '2018-12-02 14:30:00',
                'modified' => '2018-12-02 14:30:00',
            ],
        ];
        parent::init();
    }
}
