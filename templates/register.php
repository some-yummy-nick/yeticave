<form class="form container<?= !empty($errors) ? " form--invalid" : "" ?>" method="post" enctype="multipart/form-data">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item<?= isset($errors["email"]) ? " form__item--invalid" : "" ?>">
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail"
               value="<?= isset($form["email"]) ? $form["email"] : "" ?>">
        <? if (isset($errors["email"])) : ?>
            <span class="form__error"><?= $errors["email"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item<?= isset($errors["password"]) ? " form__item--invalid" : "" ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль"
               value="<?= isset($form["password"]) ? $form["password"] : "" ?>">
        <? if (isset($errors["password"])) : ?>
            <span class="form__error"><?= $errors["password"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item<?= isset($errors["name"]) ? " form__item--invalid" : "" ?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="name" placeholder="Введите имя"
               value="<?= isset($form["name"]) ? $form["name"] : "" ?>">
        <? if (isset($errors["name"])) : ?>
            <span class="form__error"><?= $errors["name"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item<?= isset($errors["contacts"]) ? " form__item--invalid" : "" ?>">
        <label for="contacts">Контактные данные*</label>
        <textarea id="contacts" name="contacts"
                  placeholder="Напишите как с вами связаться"><?= isset($form["contacts"]) ? $form["contacts"] : "" ?></textarea>
        <? if (isset($errors["contacts"])) : ?>
            <span class="form__error"><?= $errors["contacts"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <? if (isset($form["user-photo"])): ?>
            <div class="preview active">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="/uploads/<?= isset($form["user-photo"]) ? $form["user-photo"] : "" ?>" width="113"
                         height="113"
                         alt="Ваш аватар">
                </div>
            </div>
        <? else: ?>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="" name="user-photo">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        <? endif; ?>

        <? if (isset($errors["user-photo"])) : ?>
            <span class="form__error"><?= $errors["user-photo"] ?></span>
        <? endif; ?>
    </div>
    <? if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <? endif; ?>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
