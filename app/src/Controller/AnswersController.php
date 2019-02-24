<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * Answers Controller
 */
class AnswersController extends AppController
{
    const ANSWER_UPPER_LIMIT = 100;

    /**
     * {@inheritdoc}
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->request->allowMethod(['post']);
    }

    /**
     * 回答投稿処理
     *
     * @return \Cake\Http\Response|null 回答投稿後に質問詳細画面へ遷移する
     */
    public function add()
    {
        $answer = $this->Answers->newEntity($this->request->getData());
        $count = $this->Answers
            ->find()
            ->where(['question_id' => $answer->question_id])
            ->count();

        if ($count >= self::ANSWER_UPPER_LIMIT) {
            $this->Flash->error('回答の上限数に達しました');

            return $this->redirect(['controller' => 'Questions', 'action' => 'view', $answer->question_id]);
        }

        $answer->user_id = $this->Auth->user('id');
        if ($this->Answers->save($answer)) {
            $this->Flash->success('回答を投稿しました');
        } else {
            $this->Flash->error('回答の投稿に失敗しました');
        }

        return $this->redirect(['controller' => 'Questions', 'action' => 'view', $answer->question_id]);
    }

    /**
     * 回答削除処理
     *
     * @param int $id 回答ID
     * @return \Cake\Http\Response|null 回答削除後に質問詳細画面へ遷移する
     */
    public function delete(int $id)
    {
        $answer = $this->Answers->get($id);
        $questionId = $answer->question_id;
        if ($answer->user_id !== $this->Auth->user('id')) {
            $this->Flash->error('他のユーザーの回答を削除することはできません');

            return $this->redirect(['controller' => 'Questions', 'action' => 'view', $questionId]);
        }

        if ($this->Answers->delete($answer)) {
            $this->Flash->success('回答を削除しました');
        } else {
            $this->Flash->error('回答の削除に失敗しました');
        }

        return $this->redirect(['controller' => 'Questions', 'action' => 'view', $questionId]);
    }
}
