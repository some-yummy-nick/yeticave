
<form class="form container <?= !empty($errors) ? " form--invalid" : "" ?>" method="post">
    <? if($success) :?>
        Теперь вы можете войти, используя свой email и пароль
    <?endif; ?>
    <h2>Вход</h2>
    <div class="form__item<?= isset($errors["email"]) ? " form__item--invalid" : "" ?>">
        <label for="email">E-mail*(ignat.v@gmail.com)</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?= isset($form["email"]) ? $form["email"] : "" ?>"  >
        <? if (isset($errors["email"])) : ?>
            <span class="form__error"><?= $errors["email"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item form__item--last<?= isset($errors["password"]) ? " form__item--invalid" : "" ?>">
        <label for="password">Пароль*(ug0GdVMi)</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль"  >
        <? if (isset($errors["password"])) : ?>
            <span class="form__error"><?= $errors["password"] ?></span>
        <? endif; ?>
    </div>
    <? if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <? endif; ?>
    <button type="submit" class="button">Войти</button>
    <a class="text-link" href="register.php">Зарегистрироваться</a>
</form>
