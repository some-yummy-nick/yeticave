<?
$eightHoursInSeconds = 28800;
?>
<section class="rates container">
    <h2>Мои ставки</h2>
    <? if ($lots) : ?>
        <table class="rates__list">
            <?php foreach ($lots as $lot): ?>
                <tr class="rates__item <?= (strtotime($lot["date_end"]) - time() < 0) ? "rates__item--end" : "" ?>
           <? //= $lot["winner_id"] ? "rates__item--win" : "" ?>
">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $lot["image"] ?>" width="54" height="40" alt="<?= $lot["name"] ?>">
                        </div>
                        <h3 class="rates__title">
                            <a href="/lot.php?lot_id=<?= $lot["id"] ?>">
                                <?= $lot["name"] ?>
                            </a>
                            <? //= $lot["winner_id"] ? "<p>" . $lot["contacts"] . "</p>" : "" ?>
                        </h3>
                    </td>
                    <td class="rates__category">
                        <?= $lot["category"] ?>
                    </td>
                    <td class="rates__timer">
                        <? // if ($lot["winner_id"]): ?>
                        <!--<div class="timer timer--win">Ставка выиграла</div>-->
                        <? // else: ?>
                        <? if (strtotime($lot["date_end"]) - time() < 0) : ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <? else: ?>
                            <div class="timer<?= (strtotime($lot["date_end"]) - time(
                                )) < $eightHoursInSeconds ? " timer--finishing" : "" ?>">
                                <? showTimeEnd($lot["date_end"]) ?></div>
                        <? endif; ?>
                    </td>
                    <td class="rates__price"><?= setNumberToFormat($lot["price"]) ?> р</td>
                    <td class="rates__time"><?= showDate($lot["time"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <? else:?>
    <p>Ставок пока нет</p>
    <? endif; ?>
</section>
