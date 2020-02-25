
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($lots as $key => $item) : ?>
                <tr class="rates__item <?= (intval($item['winner_id']) === intval($user_id)) ? 'rates__item--win' : ''; ?><?= ((strtotime($item['date_end']) < strtotime('now')) && (intval($item['winner_id']) !== intval($user_id))) ? 'rates__item--end' : ''; ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $item['image']; ?>" width="54" height="40" alt="Сноуборд">
                        </div>
                        <div>
                            <h3 class="rates__title"><a
                                    href="lot.php?lot_id=<?= $item['lot_id']; ?>"><?= htmlspecialchars($item['name']); ?></a>
                            </h3>
                            <?php if (intval($item['winner_id']) === intval($user_id)) : ?>
                                <p><?= $item['contacts']; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= $item['category']; ?>
                    </td>
                    <td class="rates__timer">
                        <?php if (strtotime($item['date_end']) > time()) : ?>
                            <div
                                class="timer <?= (strtotime($item['date_end']) - strtotime('now') <= $time_to_close && strtotime($item['date_end']) - strtotime('now') > 0) ? 'timer--finishing' : '' ?>">
                                <span><? showTimeEnd($item['date_end']); ?></span>
                            </div>
                        <?php elseif (intval($item['winner_id']) === intval($user_id)) : ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php else : ?>
                            <div class="timer timer--end">Торги окончены</div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?= get_formatted_amount($item['price']).' р'; ?>
                    </td>
                    <td class="rates__time">
                        <?= show_time(strtotime($item['date'])); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
