<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Answers Model
 */
class AnswersTable extends Table
{
    /**
     * @inheritdoc
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('answers'); // 使用されるテーブル名
        $this->setDisplayField('id'); // list形式でデータ取得される際に使用されるカラム
        $this->setPrimaryKey('id'); // プライマリキーとなるカラム名

        $this->addBehavior('Timestamp'); // created及びmodifiedカラムを自動設定する

        $this->hasMany('Questions', [
            'foreignKey' => 'question_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * バリデーションルールの定義
     *
     * @param \Cake\Validation\Validator $validator バリデーションインスタンス
     * @return \Cake\Validation\Validator バリデーションインスタンス
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id', 'IDが不正です')
            ->allowEmpty('id', 'create', 'IDが不正です');

        $validator
            ->scalar('body', '回答内容が不正です')
            ->requirePresence('body', 'create', '回答内容が不正です')
            ->notEmpty('body', '回答内容は必ず入力してください')
            ->maxLength('body', 140, '回答内容は140字以内で入力してください');

        return $validator;
    }

    /**
     * ルールチェッカーを作成する
     *
     * @param \Cake\ORM\RulesChecker $rules ルールチェッカーのオブジェクト
     * @return \Cake\ORM\RulesChecker ルールチェッカーのオブジェクト
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(
            ['question_id'],
            'Questions',
            '質問が既に削除されているため回答することが出来ません'
        ));

        return $rules;
    }
}
