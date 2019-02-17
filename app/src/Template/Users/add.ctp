<h2 class="mb-3"><i class="fas fa-user"></i> ユーザー登録</h2>

<?= $this->Form->create($user) ?>
<?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50]) ?>
<?= $this->Form->control('password', ['label' => 'パスワード', 'value' => '', 'maxLength' => 50]); ?>
<?= $this->Form->control('password_confirm', ['label' => 'パスワード確認用', 'value' => '', 'required' => true, 'type' => 'password', 'maxLength' => 50]) ?>
<?= $this->Form->control('nickname', ['label' => 'ニックネーム', 'maxLength' => 50]) ?>
<?= $this->Form->button('登録する', ['class' => 'btn btn-warning mb-5']) ?>
<?= $this->Form->end() ?>
