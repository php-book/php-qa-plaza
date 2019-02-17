<h2 class="mb-3"><i class="fas fa-pen"></i> 質問を投稿する</h2>

<?= $this->Form->create($question) ?>
<?= $this->Form->control('body', ['type' => 'textarea', 'label' => false, 'maxLength' => 200]) ?>
<?= $this->Form->button('投稿する', ['class' => 'btn btn-warning']) ?>
<?= $this->Form->end() ?>
