<?
$eightHoursInSeconds = 28800;
?>
<section class="lots">
    <h2>Результаты поиска по запросу «<span><?= $search ?></span>»</h2>
    <? if ($lots) : ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot["image"] ?>" width="350" height="260" alt="<?= $lot["name"] ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot["category"] ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?lot_id=<?= $lot["id"] ?>"><?= $lot["name"] ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">
                                    <?= $lot["count_bet"] ? $lot["count_bet"] . " " . getWord(
                                            $lot["count_bet"],
                                            ["ставка", "ставки", "ставок"]
                                        ) : "Стартовая цена" ?>
                                    </span>
                                <span class="lot__cost"><?= setNumberToFormat($lot["price"]) ?><b
                                        class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer<?= (strtotime($lot["date_end"]) - time(
                                )) < $eightHoursInSeconds ? " timer--finishing" : "" ?>">
                                <span><? showTimeEnd($lot["date_end"]) ?></span>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <? else : ?>
        <p>Лотов пока нет</p>
    <? endif; ?>
</section>
<?= $pagination; ?>
