<?php include_once('functions.php');?>

</main>
</div>
<? if (!logged_in()): ?>
<div class="modal overlay">
    <div class="container modal__body" id="login-modal">
        <div class="modal-close">
            <button class="modal-close__btn chest-icon"></button>
        </div>
        <section class="wrapper">
            <h2 class="tweet-form__title">Введите логин и пароль</h2>
            <? if($error && HOST . $_SERVER['REQUEST_URI'] !== HOST . '/register.php'): ?>
                <div class="tweet-form__error"><?= $error ?></div>
            <? endif; ?>
            <div class="tweet-form__subtitle">Если у вас нет логина, пройдите <a href="<?= get_url('register.php')?>">регистрацию</a></div>
            <form class="tweet-form" method="post" action="<?= get_url('includes/sign_in.php')?>">
                <div class="tweet-form__wrapper_inputs">
                    <input type="text" class="tweet-form__input" placeholder="Логин" required name="login">
                    <input type="password" class="tweet-form__input" placeholder="Пароль" required name="password">
                </div>
                <div class="tweet-form__btns_center">
                    <button class="tweet-form__btn_center" type="submit">Войти</button>
                </div>
            </form>
        </section>
    </div>
</div>
<? endif;?>
<script src="<?= get_url('js/scripts.js')?>"></script>
    <? if($error && HOST . $_SERVER['REQUEST_URI'] !== HOST . '/register.php'): ?>
    <script>openModal()</script>
    <? endif; ?>
</body>
</html>

