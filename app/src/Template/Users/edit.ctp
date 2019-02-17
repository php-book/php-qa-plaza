<h2 class="mb-3"><i class="fas fa-user-edit"></i> ユーザー編集</h2>

<?= $this->Form->create($user) ?>
<?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50]) ?>
<?= $this->Form->control('nickname', ['label' => 'ニックネーム', 'maxLength' => 50]) ?>
<?= $this->Form->button('更新する', ['class' => 'btn btn-warning']) ?>
<?= $this->Form->end() ?>

<p class="mt-3">
    <?= $this->Html->link('パスワードを更新する場合はこちら', ['action' => 'password']) ?>
</p>