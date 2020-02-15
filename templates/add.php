<form class="form form--add-lot container<?= !empty($errors) ? " form--invalid" : "" ?>" method="post"
      enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item<?= isset($errors["lot-name"]) ? " form__item--invalid" : "" ?>">
            <label for="lot-name">Наименование*</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required
                   value="<?= isset($form["lot-name"]) ? $form["lot-name"] : "" ?>">
            <? if (isset($errors["lot-name"])) : ?>
                <span class="form__error"><?= $errors["lot-name"] ?></span>
            <? endif; ?>
        </div>
        <div class="form__item<?= isset($errors["lot-category"]) ? " form__item--invalid" : "" ?>">
            <label for="category">Категория*</label>
            <select id="category" name="lot-category" required>
                <option>Выберите категорию</option>
                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?= $category["id"] ?>" <?= ($category["name"] == $form["lot-category"]) ? "selected" : "" ?>><?= $category["name"] ?></option>
                <?php endforeach; ?>
            </select>
            <? if (isset($errors["lot-category"])) : ?>
                <span class="form__error"><?= $errors["lot-category"] ?></span>
            <? endif; ?>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors["lot-message"]) ? " form__item--invalid" : "" ?>">
        <label for="message">Описание*</label>
        <textarea id="message" name="lot-message" placeholder="Напишите описание лота" required
        ><?= isset($form["lot-message"]) ? $form["lot-message"] : "" ?></textarea>
        <? if (isset($errors["lot-message"])) : ?>
            <span class="form__error"><?= $errors["lot-message"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__item form__item--file <?= isset($errors["lot-photo"]) ? " form__item--invalid" : "" ?>">
        <label>Изображение*</label>
        <? if (isset($form["lot-photo"])): ?>
            <div class="preview active">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="/uploads/<?= isset($form["lot-photo"]) ? $form["lot-photo"] : "" ?>" width="113"
                         height="113"
                         alt="Изображение лота">
                </div>
            </div>
        <? else: ?>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="" name="lot-photo" required>
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        <? endif; ?>
        <? if (isset($errors["lot-photo"])) : ?>
            <span class="form__error"><?= $errors["lot-photo"] ?></span>
        <? endif; ?>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small<?= isset($errors["lot-rate"]) ? " form__item--invalid" : "" ?>">
            <label for="lot-rate">Начальная цена*</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required
                   value="<?= isset($form["lot-rate"]) ? $form["lot-rate"] : "" ?>">
            <? if (isset($errors["lot-rate"])) : ?>
                <span class="form__error"><?= $errors["lot-rate"] ?></span>
            <? endif; ?>
        </div>
        <div class="form__item form__item--small<?= isset($errors["lot-step"]) ? " form__item--invalid" : "" ?>">
            <label for="lot-step">Шаг ставки*</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" required
                   value="<?= isset($form["lot-step"]) ? $form["lot-step"] : "" ?>">
            <? if (isset($errors["lot-step"])) : ?>
                <span class="form__error"><?= $errors["lot-step"] ?></span>
            <? endif; ?>
        </div>
        <div class="form__item <?= isset($errors["lot-date-end"]) ? " form__item--invalid" : "" ?>">
            <label for="lot-date-end">Дата окончания торгов*</label>
            <input class="form__input-date" id="lot-date-end" type="date" name="lot-date-end" required
                   value="<?= isset($form["lot-date-end"]) ? $form["lot-date-end"] : "" ?>">
            <? if (isset($errors["lot-date-end"])) : ?>
                <span class="form__error"><?= $errors["lot-date-end"] ?></span>
            <? endif; ?>
        </div>
    </div>
    <? if (!empty($errors)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <? endif; ?>
    <button type="submit" class="button">Добавить лот</button>
</form>
