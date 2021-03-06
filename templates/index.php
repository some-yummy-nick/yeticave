<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <? if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <li class="promo__item promo__item--<?= $category["english_name"] ?>">
                    <a class="promo__link"
                       href="/lots.php?category_id=<?= $category["id"] ?>"><?= $category["name"] ?></a>
                </li>
            <?php endforeach; ?>
        <? endif; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <a href="/lot.php?lot_id=<?= $lot["id"] ?>">
                        <img src="<?= $lot["image"] ?>" width="350" height="260" alt="<?= $lot["name"] ?>">
                    </a>
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot["category"] ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="/lot.php?lot_id=<?= $lot["id"] ?>"><?= $lot["name"] ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= get_formatted_amount($lot["price"]) ?><b
                                    class="rub">р</b></span>
                        </div>
                        <?php if (strtotime($lot['date_end']) > time()) : ?>
                            <div
                                class="lot__timer timer <?= (strtotime($lot['date_end']) - strtotime('now') <= $time_to_close && strtotime($lot['date_end']) - strtotime('now') > 0) ? 'timer--finishing' : '' ?>">
                                <span><? showTimeEnd($lot['date_end']); ?></span>
                            </div>
                        <?php else : ?>
                            <div class="lot-item__timer timer timer--end">Торги окончены</div>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
