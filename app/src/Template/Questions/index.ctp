<h2 class="mb-3"><i class="fas fa-list"></i> 質問一覧</h2>

<?php if ($questions->isEmpty()): ?>
    <div class="card mb-2">
        <div class="card-body">
            <h5 class="card-title text-center">表示できる質問がありません。</h5>
        </div>
    </div>
<?php else: ?>
    <p><?= $this->Paginator->counter(['format' => '全{{pages}}ページ中{{page}}ページ目を表示しています']) ?></p>
    <?php foreach ($questions as $question): ?>
        <div class="card mb-2">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-user-circle"></i> <?= 'たろう' // @TODO ユーザー管理機能実装時に修正する ?>
                </h5>
                <p class="card-text"><?= nl2br(h($question->body)) ?></p>
                <p class="card-subtitle mb-2 text-muted">
                    <small><?= h($question->created) ?></small>
                    <small>
                        <i class="fas fa-comment-dots"></i> <?= $this->Number->format($question->answered_count) ?>
                    </small>
                </p>
                <?= $this->Html->link('詳細へ', ['action' => 'view', $question->id], ['class' => 'card-link']) ?>
                <?= $this->Form->postLink('削除する', ['action' => 'delete', $question->id],
                    ['confirm' => '質問を削除します。よろしいですか？'], ['class' => 'card-link']) ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< 最初へ') ?>
            <?= $this->Paginator->prev('< 前へ') ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next('次へ >') ?>
            <?= $this->Paginator->last('最後へ >>') ?>
        </ul>
    </div>

<?php endif; ?>
