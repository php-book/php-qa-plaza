<h2 class="mb-3"><i class="fas fa-key"></i> パスワード更新</h2>

<?= $this->Form->create($user) ?>
<?= $this->Form->control('password_current', ['label' => '現在のパスワード', 'value' => '', 'required' => true, 'type' => 'password', 'maxLength' => 50]) ?>
<?= $this->Form->control('password', ['label' => '新しいパスワード', 'value' => '', 'maxLength' => 50]) ?>
<?= $this->Form->control('password_confirm', ['label' => '新しいパスワード確認用', 'value' => '', 'required' => true, 'type' => 'password', 'maxLength' => 50]) ?>
<?= $this->Form->button('更新する', ['class' => 'btn btn-warning mb-5']) ?>
<?= $this->Form->end() ?>