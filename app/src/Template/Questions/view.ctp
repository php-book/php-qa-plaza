<h2 class="mb-3"><i class="fas fa-flag"></i> 質問</h2>

<section class="question">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <i class="fas fa-user-circle"></i> <?= h($question->user->nickname) ?>
            </h5>
            <p class="card-text"><?= nl2br(h($question->body)) ?></p>
            <p class="card-subtitle mb-2 text-muted">
                <small><?= h($question->created) ?></small>
                <small><i class="fas fa-comment-dots"></i> <?= $this->Number->format($answers->count()) ?></small>
            </p>
        </div>
    </div>
</section>

<section class="answer mb-4">
    <?php if ($answers->isEmpty()): ?>
        <div class="card w-75 mb-2 ml-auto">
            <div class="card-body">
                <h5 class="card-title text-center">回答はまだありません。</h5>
            </div>
        </div>
    <?php else: ?>
        <div class="w-75 ml-auto">
            <h5><i class="fas fa-reply"></i> 回答</h5>
        </div>
        <?php foreach ($answers as $answer): ?>
            <div class="card w-75 mb-2 ml-auto">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-circle"></i> <?= h($answer->user->nickname) ?></h5>
                    <p class="card-text"><?= nl2br(h($answer->body)) ?></p>
                    <p class="card-subtitle mb-2 text-muted">
                        <small><?= h($answer->created) ?></small>
                        <?php if ($this->request->getSession()->read('Auth.User.id') === $answer->user_id): ?>
                            <?= $this->Form->postLink('削除する', ['controller' => 'Answers', 'action' => 'delete', $answer->id],
                                ['confirm' => '回答を削除します。よろしいですか？'], ['class' => 'card-link']) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<section class="answer-post mb-5">
    <h2 class="mb-3"><i class="fas fa-comment-dots"></i> 回答する</h2>
    <?php if ($this->request->getSession()->read('Auth.User.id')): ?>
        <?php if ($answers->count() >= \App\Controller\AnswersController::ANSWER_UPPER_LIMIT): ?>
            <p class="text-center">回答数が上限に達しているためこれ以上回答することはできません</p>
        <?php else: ?>
            <?= $this->Form->create($newAnswer, ['url' => '/answers/add']) ?>
            <?php
            echo $this->Form->control('body', [
                'type' => 'textarea',
                'label' => false,
                'value' => '',
                'maxLength' => 200
            ]);
            echo $this->Form->hidden('question_id', ['value' => $question->id]);
            ?>
            <?= $this->Form->button('投稿する', ['class' => 'btn btn-warning']) ?>
            <?= $this->Form->end() ?>
        <?php endif; ?>
    <?php else: ?>
        <p>回答するには<?= $this->Html->link('ログイン', ['controller' => 'Login', 'action' => 'index']) ?>が必要です</p>
    <?php endif; ?>
</section>
