<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= "PHP質問広場: {$this->fetch('title')}" ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->script('https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js') ?>
    <?= $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.bundle.js') ?>
    <?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.css') ?>
    <?= $this->Html->css('https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@5.6.3/css/all.css') ?>
    <?= $this->Html->css('https://fonts.googleapis.com/css?family=Varela+Round') ?>
    <?= $this->Html->css('custom.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<header class="header">
    <nav class="navbar navbar-expand-lg navbar-light background-white">
        <div class="container col-md-7 col-12">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tree"></i> PHP質問広場
            </a>

            <button
                type="button"
                class="navbar-toggler"
                data-toggle="collapse"
                data-target="#Navber">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="Navber">
                <?php if ($this->request->getSession()->read('Auth.User.id')): ?>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <?= $this->Html->link('質問一覧',
                            ['controller' => 'Questions', 'action' => 'index'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('質問を投稿する',
                            ['controller' => 'Questions', 'action' => 'add'], ['class' => 'nav-link']) ?>
                        </li>
                    </ul>
                <?php endif; ?>

                <ul class="navbar-nav ml-auto">
                    <?php if ($this->request->getSession()->read('Auth.User.id')): ?>
                        <li class="nav-item">
                            <?= $this->Html->link($this->request->getSession()->read('Auth.User.nickname'),
                                ['controller' => 'Users', 'action' => 'edit'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('ログアウト',
                                ['controller' => 'Logout', 'action' => 'index'], ['class' => 'nav-link']) ?>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= $this->Html->link('ユーザー登録',
                                ['controller' => 'Users', 'action' => 'add'], ['class' => 'nav-link']) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link('ログイン',
                                ['controller' => 'Login', 'action' => 'index'], ['class' => 'nav-link']) ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<?= $this->Flash->render() ?>

<?= $this->fetch('sub-header') ?>

<section class="main background-light">
    <div class="container pt-5 col-md-7 col-12">
        <?= $this->fetch('content') ?>
    </div>
</section>

<footer class="footer background-dark d-flex justify-content-center align-items-center">
    <small>© <?= date('Y') ?> PHP質問広場 All Rights Reserved.</small>
</footer>

</body>
</html>
