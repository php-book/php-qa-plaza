<?php if (!$this->request->getSession()->read('Auth.User.id')): ?>
    <?php $this->start('sub-header'); ?>
        <section class="sub-header">
            <div class="container col-md-7 col-12">
                <div class="jumbotron mt-4">
                    <p class="display-4">ようこそ！</p>
                    <p class="lead">質問はきちんと言葉にできなくても、かぶっていてもOKです。気軽に相談してみてくださいね。</p>
                    <hr class="my-4">
                    <p>さっそくはじめてみましょう。</p>
                    <?= $this->Html->link('ユーザー登録',
                        ['controller' => 'Users', 'action' => 'add'], ['class' => 'btn btn-warning btn-lg']) ?>
                </div>
            </div>
        </section>
    <?php $this->end(); ?>
<?php endif; ?>
<h2 class="text-center mb-4">PHP質問広場って？</h2>
<div class="row text-center">
    <div class="col-sm">
        <div class="circle-div mb-3">
            <span class="circle-number">1</span>
        </div>
        <h3><i class="fa fa-flag"></i> 相談</h3>
        <p>PHP質問広場は気軽にPHPの相談ができます。</p>
    </div>
    <div class="col-sm">
        <div class="circle-div mb-3">
            <span class="circle-number">2</span>
        </div>
        <h3><i class="fas fa-comments"></i> 回答</h3>
        <p>PHPが得意な人から回答がつきます。</p>
    </div>
    <div class="col-sm">
        <div class="circle-div mb-3">
            <span class="circle-number">3</span>
        </div>
        <h3><i class="fas fa-user-graduate"></i> 学び</h3>
        <p>回答をしっかり読んで学びにしましょう。</p>
    </div>
</div>
