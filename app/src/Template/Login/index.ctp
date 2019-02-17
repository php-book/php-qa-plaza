<h2 class="mb-3"><i class="fas fa-sign-in-alt"></i> ログイン</h2>

<?= $this->Form->create() ?>
<?= $this->Form->control('username', ['label' => 'ユーザー名', 'maxLength' => 50]) ?>
<?= $this->Form->control('password', ['label' => 'パスワード', 'maxLength' => 50]) ?>
<?= $this->Form->button('ログイン', ['class' => 'btn btn-warning']) ?>
<?= $this->Form->end() ?>
