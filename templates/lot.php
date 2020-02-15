<?
$eightHoursInSeconds = 28800;
?>

<? if (isset($lot)): ?>
    <section class="lot-item container">
        <h2><?= $lot["name"] ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot["image"] ?>" width="730" height="548" alt="<?= $lot["name"] ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot["category"] ?></span></p>
                <p class="lot-item__description">Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив
                    снег
                    мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот
                    снаряд
                    отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
                    кэмбер
                    позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,
                    просто
                    посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла
                    равнодушным.</p>
            </div>
            <div class="lot-item__right">
                <? if ($is_auth) : ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer <?= (strtotime($lot["date_end"]) - time(
                            )) < $eightHoursInSeconds ? " timer--finishing" : "" ?>">
                            <? showTimeEnd($lot["date_end"])?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= setNumberToFormat($lot["price"]) ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= setNumberToFormat($lot["price"] + $lot["step"]) ?> р</span>
                            </div>
                        </div>
                        <form class="lot-item__form<?= !empty($errors) ? " form--invalid" : "" ?>" method="post">
                            <p class="lot-item__form-item<?= isset($errors["cost"]) ? " form__item--invalid" : "" ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost"
                                       placeholder="<?= setNumberToFormat($lot["price"] + $lot["step"]) ?>">
                                <? if (isset($errors["cost"])) : ?>
                                    <span class="form__error"><?= $errors["cost"] ?></span>
                                <? endif; ?>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <? endif; ?>
                <? if ($bets): ?>
                    <div class="history">
                        <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($bets as $bet): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= $bet["name"] ?></td>
                                    <td class="history__price"><?= setNumberToFormat($bet["price"]) ?> р</td>
                                    <td class="history__time"><?= showDate($bet["date"]) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </section>
<? else: ?>
    <h1>Товар с таким id не найден</h1>
<? endif; ?>
