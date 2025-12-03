<?php
/* /engine/app/functions.php */
declare(strict_types=1);

require_once __DIR__ . '/db.php';         // создаёт $pdo

/* ---------- SYSTEM ---------- */

function print_arr($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

/**
 * Изменяет количество фриспинов у пользователя.
 * @param int $uid User ID
 * @param int $amount Количество (отрицательное для списания, положительное для начисления)
 * @return int Актуальный баланс фриспинов
 */
function setUserFreeSpinsById(int $uid, int $amount): int
{
    global $pdo;

    // Обновляем баланс, не допуская ухода в минус
    $sql = "UPDATE user_altcoins 
            SET RIX_freespin = GREATEST(0, RIX_freespin + :amount) 
            WHERE user_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id'     => $uid,
        'amount' => $amount
    ]);

    // Получаем актуальный баланс для возврата
    $stmt = $pdo->prepare('SELECT RIX_freespin FROM user_altcoins WHERE user_id = :id LIMIT 1');
    $stmt->execute(['id' => $uid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int)$row['RIX_freespin'] : 0;
}

function createDeal(array $data): int
{
    global $pdo;

    $withoutCorridor = calculateDealRateCorridor($data['rate_without_RIX']);
    $withCorridor    = calculateDealRateCorridor($data['rate_with_RIX']);

    $sql = 'INSERT INTO deals (
                team_id, region_id, need_confirm, payout_mode, deal_size,
                product, amount_min, amount_max, term_days,
                rate_without_RIX, rate_without_RIX_min, rate_without_RIX_max,
                rate_with_RIX, rate_with_RIX_min, rate_with_RIX_max,
                config_note, config_note_en, config_note_cn, config_note_ar
            ) VALUES (
                :team_id, :region_id, :need_confirm, :payout_mode, :deal_size, 
                :product, :amount_min, :amount_max, :term_days,
                :rate_without_RIX, :rate_without_RIX_min, :rate_without_RIX_max,
                :rate_with_RIX, :rate_with_RIX_min, :rate_with_RIX_max,
                :config_note, :config_note_en, :config_note_cn, :config_note_ar
            )';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'team_id'              => $data['team_id'],
        'region_id'            => $data['region_id'],
        'need_confirm'         => $data['need_confirm'],
        'payout_mode'          => $data['payout_mode'] ?? 'end',
        'deal_size'            => $data['deal_size'],
        'product'              => $data['product'],
        'amount_min'           => $data['amount_min'],
        'amount_max'           => $data['amount_max'],
        'term_days'            => $data['term_days'],
        'rate_without_RIX'     => $data['rate_without_RIX'],
        'rate_without_RIX_min' => $withoutCorridor['min'],
        'rate_without_RIX_max' => $withoutCorridor['max'],
        'rate_with_RIX'        => $data['rate_with_RIX'],
        'rate_with_RIX_min'    => $withCorridor['min'],
        'rate_with_RIX_max'    => $withCorridor['max'],
        'config_note'          => $data['config_note'] ?? '',
        'config_note_en'       => $data['config_note_en'] ?? '',
        'config_note_cn'       => $data['config_note_cn'] ?? '',
        'config_note_ar'       => $data['config_note_ar'] ?? '',
    ]);

    return (int)$pdo->lastInsertId();
}

function updateDeal(int $dealId, array $data): bool
{
    global $pdo;

    $withoutCorridor = calculateDealRateCorridor($data['rate_without_RIX']);
    $withCorridor    = calculateDealRateCorridor($data['rate_with_RIX']);

    $sql = 'UPDATE deals SET
                team_id = :team_id,
                region_id = :region_id,
                need_confirm = :need_confirm,
                payout_mode = :payout_mode,
                deal_size = :deal_size,
                product = :product,
                amount_min = :amount_min,
                amount_max = :amount_max,
                term_days = :term_days,
                rate_without_RIX = :rate_without_RIX,
                rate_without_RIX_min = :rate_without_RIX_min,
                rate_without_RIX_max = :rate_without_RIX_max,
                rate_with_RIX = :rate_with_RIX,
                rate_with_RIX_min = :rate_with_RIX_min,
                rate_with_RIX_max = :rate_with_RIX_max,
                config_note = :config_note,
                config_note_en = :config_note_en,
                config_note_cn = :config_note_cn,
                config_note_ar = :config_note_ar
            WHERE deal_id = :deal_id';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'team_id'              => $data['team_id'],
        'region_id'            => $data['region_id'],
        'need_confirm'         => $data['need_confirm'],
        'payout_mode'          => $data['payout_mode'] ?? 'end',
        'deal_size'            => $data['deal_size'],
        'product'              => $data['product'],
        'amount_min'           => $data['amount_min'],
        'amount_max'           => $data['amount_max'],
        'term_days'            => $data['term_days'],
        'rate_without_RIX'     => $data['rate_without_RIX'],
        'rate_without_RIX_min' => $withoutCorridor['min'],
        'rate_without_RIX_max' => $withoutCorridor['max'],
        'rate_with_RIX'        => $data['rate_with_RIX'],
        'rate_with_RIX_min'    => $withCorridor['min'],
        'rate_with_RIX_max'    => $withCorridor['max'],
        'config_note'          => $data['config_note'] ?? '',
        'config_note_en'       => $data['config_note_en'] ?? '',
        'config_note_cn'       => $data['config_note_cn'] ?? '',
        'config_note_ar'       => $data['config_note_ar'] ?? '',
        'deal_id'              => $dealId,
    ]);
}

/**
 * Рассчитывает коридор ставок вокруг базовой ставки сделки в процентах.
 * Возвращает дневные значения (например 0.0042 для 0.42%).
 */
function calculateDealRateCorridor(float $ratePercent): array
{
    // базовая дневная ставка
    $baseDaily = $ratePercent / 100;

    // коридор в 20% от базовой ставки, но не меньше 0.0001
    $delta = max(round($baseDaily * 0.2, 4), 0.0001);

    $min = round(max($baseDaily - $delta, 0), 4);
    $max = round($baseDaily + $delta, 4);

    return ['min' => $min, 'max' => $max];
}

function getAllTeams(): array
{
    global $pdo;

    $stmt = $pdo->query('SELECT * FROM teams ORDER BY team_id');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function setUserTeamId(int $uid, int $teamId): bool
{
    global $pdo;

    $sql = 'UPDATE users SET team_id = :team_id WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'team_id' => $teamId,
        'uid'     => $uid,
    ]);
}

/**
 * Возвращает все новые заявки на сделки со статусом = 0.
 *
 * @return array<int, array<string, mixed>>
 */
function getNewDealRequests(): array
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM user_deal_requests WHERE status = 0");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Возвращает все заявки на сделки, отсортированные по дате создания.
 *
 * @return array<int, array<string, mixed>>
 */
function getAllDealRequests(): array
{
    global $pdo;

    $sql = "SELECT r.*, d.product
              FROM user_deal_requests AS r
              LEFT JOIN deals AS d ON d.deal_id = r.deal_id
          ORDER BY r.created_at DESC, r.id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Возвращает заявку на сделку по её идентификатору.
 */
function getDealRequestById(int $id): ?array
{
    global $pdo;

    $sql = "SELECT r.*, d.product
              FROM user_deal_requests AS r
              LEFT JOIN deals AS d ON d.deal_id = r.deal_id
             WHERE r.id = :id
             LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ?: null;
}

/**
 * Обновляет статус заявки на сделку по её идентификатору.
 */
function setDealRequestStatusById(int $id, int $status): bool
{
    global $pdo;

    $sql = "UPDATE user_deal_requests
            SET status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':status' => $status,
        ':id'     => $id,
    ]);
}


/**
 * Возвращает статусы последних заявок пользователя на сделки,
 * чтобы фронт мог отобразить нужные кнопки.
 *
 * @return array<int,int> [deal_id => status]
 */
function getUserDealRequestStatuses(int $userId): array
{
    global $pdo;

    $sql = "
        SELECT r.deal_id, r.status
          FROM user_deal_requests r
          JOIN (
              SELECT deal_id, MAX(id) AS max_id
                FROM user_deal_requests
               WHERE user_id = :uid
               GROUP BY deal_id
          ) last_req
            ON last_req.deal_id = r.deal_id AND last_req.max_id = r.id
         WHERE r.user_id = :uid
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['uid' => $userId]);

    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
}


/**
 * Разрешенные столбцы таблицы user_altcoins.
 */
function userAltcoinColumns(): array
{
    return [
        'ARKF', 'ARKK', 'BINANCE_BTCUSDT', 'BINANCE_ETHUSDT', 'BINANCE_LTCUSDT', 'BINANCE_SOLUSDT', 'BINANCE_TONUSDT',
        'BINANCE_USDCUSDT', 'BINANCE_XRPUSDT', 'BITO', 'BLOK', 'GBTC', 'IBIT', 'IVV', 'QQQ', 'SMH', 'SPY', 'VOO', 'VTI',
        'VUG', 'RIXCOIN',
    ];
}

/**
 * Нормализовать имя столбца по символу из asset_quotes или по самому имени столбца.
 */
function normalizeAltcoinColumn(string $symbolOrColumn): ?string
{
    $column = strtoupper(str_replace(':', '_', $symbolOrColumn));
    $column = preg_replace('/[^A-Z0-9_]/', '', $column ?? '');

    return in_array($column, userAltcoinColumns(), true) ? $column : null;
}

/**
 * Получить (или создать) строку с альткоинами пользователя.
 */
function getUserAltcoins(int $uid): array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM user_altcoins WHERE user_id = :uid LIMIT 1');
    $stmt->execute([':uid' => $uid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $insert = $pdo->prepare('INSERT INTO user_altcoins (user_id) VALUES (:uid)');
        $insert->execute([':uid' => $uid]);

        $stmt->execute([':uid' => $uid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$row) {
        return [];
    }

    unset($row['id'], $row['user_id']);

    return $row;
}

/**
 * Прибавить сумму к указанному альткоину пользователя.
 *
 * @return float|null Новое значение баланса или null, если столбец не поддерживается.
 */
function addUserAltcoinAmount(int $uid, string $symbolOrColumn, float $amount): ?float
{
    global $pdo;

    $column = normalizeAltcoinColumn($symbolOrColumn);
    if ($column === null) {
        return null;
    }

    // убеждаемся, что строка существует
    getUserAltcoins($uid);

    $sql = "UPDATE user_altcoins SET {$column} = ROUND({$column} + :amount, 2) WHERE user_id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':amount' => $amount,
        ':uid'    => $uid,
    ]);

    $select = $pdo->prepare("SELECT {$column} FROM user_altcoins WHERE user_id = :uid LIMIT 1");
    $select->execute([':uid' => $uid]);
    $result = $select->fetch(PDO::FETCH_ASSOC);

    return $result ? (float)$result[$column] : null;
}

/**
 * Получить баланс пользователя по символу из asset_quotes.
 */
function altcoinBalanceForSymbol(array $userAltcoins, string $symbol): ?float
{
    $column = normalizeAltcoinColumn($symbol);
    if ($column === null) {
        return null;
    }

    return isset($userAltcoins[$column]) ? (float)$userAltcoins[$column] : 0.0;
}

/**
 * Выдать рандомный NFT определенной редкости
 * Возвращает данные выданного NFT или null, если библиотека пуста
 */
function giveRandomNftToUser($userId, $rarity) {
    global $pdo;

    // 1. Выбираем случайный NFT из библиотеки нужной редкости
    // Используем RAND()
    $stmt = $pdo->prepare("SELECT * FROM nft_library WHERE rarity = :rarity ORDER BY RAND() LIMIT 1");
    $stmt->execute([':rarity' => $rarity]);
    $nftItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($nftItem) {
        // 2. Привязываем к пользователю
        $insert = $pdo->prepare("INSERT INTO user_nfts (user_id, nft_library_id) VALUES (:uid, :nft_id)");
        $insert->execute([
            ':uid' => $userId,
            ':nft_id' => $nftItem['id']
        ]);
        return $nftItem;
    }

    return null;
}

/**
 * Получить список NFT пользователя для вывода в кабинете
 */
function getUserNfts($userId) {
    global $pdo;

    $sql = "SELECT un.id as user_nft_id, un.received_at, lib.id as library_id, lib.name, lib.image_path, lib.description_ru, lib.description_en, lib.rarity, lib.price
            FROM user_nfts un
            JOIN nft_library lib ON un.nft_library_id = lib.id
            WHERE un.user_id = :uid
            ORDER BY un.received_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Получить полную библиотеку NFT с сохранением порядка редкостей
 */
function getNftLibrary(): array
{
    global $pdo;

    $sql = "SELECT id, rarity, price, name, image_path, description_ru, description_en
            FROM nft_library
            ORDER BY FIELD(rarity, 'diamond', 'gold', 'silver', 'bronze'), id ASC";

    $stmt = $pdo->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

/**
 * Проверяет, что имя таблицы рулетки соответствует ожидаемому паттерну.
 */
function isAllowedRouletteItemsTable(string $table): bool
{
    return $table === 'roulette_items' || preg_match('/^roulette_items_custom\d*$/', $table) === 1;
}

/**
 * Возвращает элементы рулетки.
 *
 * @param string $table имя таблицы, из которой читаем конфигурацию рулетки
 */
function getRouletteItems(string $table = 'roulette_items'): array
{
    global $pdo;

    if (!isAllowedRouletteItemsTable($table)) {
        $table = 'roulette_items';
    }

    $sql = "
        SELECT
            token,
            item_name,
            drop_chance,
            drop_chance_guest,
            single_prize,
            type,
            value_amount,
            image_name,
            description
        FROM {$table}
        WHERE is_active = 1
        ORDER BY sort ASC, id ASC
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    return array_map(function (array $row) {
        return [
            'token'            => $row['token'],
            'item_name'        => $row['item_name'],
            'dropChance'       => (int)$row['drop_chance'],
            'dropChance_guest' => (int)$row['drop_chance_guest'],
            'singlePrize'      => (int)$row['single_prize'],
            'type'             => $row['type'],
            'value_amount'     => (int)$row['value_amount'],
            'image_name'       => $row['image_name'],
            'description'      => $row['description'] ?? '',
        ];
    }, $rows);
}

/**
 * Возвращает имя таблицы конфигураций рулетки для пользователя по значению поля admin.
 */
function getRouletteItemsTableForUser(?string $authUser): string
{
    global $pdo;

    if ($authUser === null || $authUser === '') {
        return 'roulette_items';
    }

    $sql = "SELECT admin FROM users WHERE activation = :activation LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':activation' => $authUser]);

    $admin = $stmt->fetchColumn();

    if (!is_string($admin)) {
        return 'roulette_items';
    }

    if (preg_match('/^custom_roulette(\d*)$/', $admin, $matches)) {
        $suffix = $matches[1] ?? '';

        return 'roulette_items_custom' . $suffix;
    }

    return 'roulette_items';
}

/**
 * Проверяем, что у пользователя есть кастомная рулетка.
 */
function isCustomRouletteUser(?string $authUser): bool
{
    return getRouletteItemsTableForUser($authUser) !== 'roulette_items';
}

/**
 * Формирует конфиг для рулетки на основе данных из базы.
 * Для пользователей с admin = 'custom_roulette*' используем соответствующую таблицу roulette_items_custom*.
 *
 * @param string|null $authUser uid или email (в зависимости от того, что ты туда кладёшь)
 */
function getRouletteConfig(?string $authUser): array
{
    $cdnHost = $_ENV['CLEAR_URL'] ?? ($_SERVER['HTTP_HOST'] ?? '');
    $cdnBase = $cdnHost ? 'https://' . $cdnHost . '/uploads/roulette' : '/uploads/roulette';

    $rouletteTable = getRouletteItemsTableForUser($authUser);

    return [
        'version'      => 1,
        'generated_at' => gmdate(DATE_ATOM),
        'settings'     => [
            'itemsCount'         => 100,
            'transitionDuration' => 5,
            'spinPrice'          => 100,
            'guestSpinCount'     => 3,
            'cdnBase'            => $cdnBase,
            'imageFolder'        => $cdnBase . '/',
        ],
        // ВАЖНО: сюда прокидываем флаг кастомной рулетки
        'items'        => getRouletteItems($rouletteTable),
    ];
}


/**
 * Возвращает список призов рулетки для пользователя.
 *
 * Каждый элемент:
 * [
 *   'created_at'  => '2024-08-21 12:34:56',
 *   'prize_token' => 'ABC123',
 *   'spent'       => 10,
 *   'item_name'   => '500 RIX Coin',
 *   'description' => '…' | null,
 * ]
 */
function getRoulettePrizesByUserId(int $uid): array
{
    global $pdo;

    $sql = "
        SELECT
            rp.created_at,
            rp.prize_token,
            rp.spent,
            ri.item_name,
            ri.description
        FROM roulette_prize AS rp
        LEFT JOIN roulette_items AS ri
               ON rp.prize_token = ri.token
        WHERE rp.user_id = :uid
        ORDER BY rp.created_at DESC, rp.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $uid]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}



/**
 * Поддерживает оба варианта:
 * 1) ?prize[]=abc&prize[]=erv&...
 * 2) ?prize=abc,erv,efgr,fgt,ddd
 */
function getPrizeListPreserveDuplicates(string $key = 'prize'): array
{
    $raw = $_GET[$key] ?? (array_key_exists($key.'[]', $_GET) ? $_GET[$key.'[]'] : null);
    if ($raw === null) {
        return [];
    }

    $tokens = [];
    $push = static function (string $s) use (&$tokens) {
        // режем по запятым, убираем пустые и пробелы, НО не удаляем дубликаты
        foreach (preg_split('/\s*,\s*/u', $s, -1, PREG_SPLIT_NO_EMPTY) as $t) {
            $t = trim($t);
            if ($t !== '') {
                $tokens[] = $t; // дубли остаются
            }
        }
    };

    if (is_array($raw)) {
        foreach ($raw as $item) {
            $push((string)$item);
        }
    } else {
        $push((string)$raw);
    }

    return $tokens; // без array_unique — дубли сохранены
}

/**
 * Парсит призы из $_POST['roulette_prize'].
 * Поддерживает и массив (?roulette_prize[]=A&roulette_prize[]=B) и строку "A,B".
 * Дубликаты и порядок сохраняются.
 */
function getRoulettePrizesFromPost(string $key = 'roulette_prize'): array
{
    $raw = $_POST[$key] ?? null;
    if ($raw === null) {
        return [];
    }

    $tokens = [];
    $push = static function (string $s) use (&$tokens) {
        foreach (preg_split('/\s*,\s*/u', $s, -1, PREG_SPLIT_NO_EMPTY) as $t) {
            $t = trim($t);
            if ($t !== '') {
                // ограничим под схему varchar(128)
                $tokens[] = mb_substr($t, 0, 128);
            }
        }
    };

    if (is_array($raw)) {
        foreach ($raw as $item) {
            $push((string)$item);
        }
    } else {
        $push((string)$raw);
    }

    return $tokens; // без array_unique — дубли остаются
}

/**
 * Помечает запись user_login как скрытую (hide = 1) по её ID.
 *
 * @param int $id
 * @return bool true при успешном выполнении
 */
function setHideToUserLoginId(int $id): bool {
    global $pdo;

    $stmt = $pdo->prepare("UPDATE user_login SET hide = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}


/**
 * Возвращает пары логинов с одинаковыми IP и User-Agent, но разными email, для заданного IP.
 * Результат — массив объектов (stdClass), аналогично mysqli_fetch_object().
 *
 * @param string $ip
 * @return array
 */
function getCheatersByIP(string $ip): array
{
    global $pdo;

    $sql = "
        SELECT DISTINCT
            u1.email AS email1,
            u2.email AS email2,
            ul1.user_ip,
            ul1.user_agent,
            ul1.date AS date1,
            ul2.date AS date2,
            u1.uid  AS uid1,
            u2.uid  AS uid2,
            ul1.id  AS id1,
            ul2.id  AS id2
        FROM user_login ul1
        JOIN user_login ul2
              ON ul1.user_id < ul2.user_id
        JOIN users u1 ON ul1.user_id = u1.uid
        JOIN users u2 ON ul2.user_id = u2.uid
        WHERE ul1.user_ip   = ul2.user_ip
          AND ul1.user_agent = ul2.user_agent
          AND u1.email <> u2.email
          AND ul1.hide = 0
          AND ul2.hide = 0
          AND ul1.user_ip = :ip
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['ip' => $ip]);

    return $stmt->fetchAll(PDO::FETCH_OBJ) ?: [];
}


/**
 * Ищет пары/группы пользователей с одинаковыми IP и User-Agent начиная с даты $startDate.
 * Возвращает массив объектов (stdClass), как и mysqli_fetch_object().
 *
 * @param string $startDate Формат 'YYYY-MM-DD' или 'YYYY-MM-DD HH:MM:SS'
 * @return array
 */
function getAllGroupedCheatersFromDate(string $startDate): array
{
    global $pdo;

    // РЕКОМЕНДУЕМЫЙ ВАРИАНТ (GROUP BY по ip и user_agent)
    $sql = "
        SELECT
            GROUP_CONCAT(DISTINCT u1.email) AS email1,
            GROUP_CONCAT(DISTINCT u2.email) AS email2,
            ul1.user_ip,
            GROUP_CONCAT(DISTINCT u1.uid)   AS uid1,
            GROUP_CONCAT(DISTINCT u2.uid)   AS uid2,
            ul1.user_agent
        FROM user_login ul1
        JOIN user_login ul2
            ON ul1.user_ip = ul2.user_ip
           AND ul1.user_agent = ul2.user_agent
           AND ul1.user_id < ul2.user_id
        JOIN users u1 ON ul1.user_id = u1.uid
        JOIN users u2 ON ul2.user_id = u2.uid
        WHERE ul1.hide = 0
          AND ul2.hide = 0
          AND ul1.date >= :startDate
        GROUP BY ul1.user_ip, ul1.user_agent
    ";

    $stmt = $pdo->prepare($sql);
    if (!$stmt->execute(['startDate' => $startDate])) {
        // Лог/обработка ошибки по желанию
        return [];
    }

    $rows = [];
    while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
        $rows[] = $obj;
    }
    return $rows;
}


/**
 * Логирует факт входа пользователя в систему.
 *
 * @param int    $uid      — ID пользователя
 * @param string $ip       — IP-адрес
 * @param bool   $isMobile — флаг: мобильное устройство или нет
 * @param string $agent    — строка User-Agent
 * @return bool            — true при успехе, false при ошибке
 */
function setNewLoginData(int $uid, string $ip, bool $isMobile, string $agent): bool {
    global $pdo;

    // Лучше явно указывать список колонок (чтобы не зависеть от порядка в БД)
    $sql = "
        INSERT INTO user_login
            (user_id, date, user_ip, user_agent, ismobile, hide)
        VALUES
            (:uid, NOW(), :user_ip, :user_agent, :ismobile, 0)
    ";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'uid'       => $uid,
        'user_ip'        => $ip,
        'user_agent'     => $agent,
        'ismobile' => $isMobile ? 1 : 0,
    ]);
}


/**
 * Возвращает все новости из таблицы news в виде массива,
 * где ключами являются ID записей.
 *
 * @return array [ id => [ … данные новости … ], … ]
 */
function getAllPromo(): array {
    global $pdo;

    $stmt = $pdo->query("SELECT * FROM promo_list ORDER BY news_date DESC, id DESC");

    $news = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $news[$row['id']] = $row;
    }

    return $news;
}

function createPromo(array $data): int
{
    global $pdo;

    $sql = "INSERT INTO promo_list
            (news_title_ru, news_title_en, raw_text_ru, raw_text_en, markup_ru, markup_en, image_path, news_date)
            VALUES (:title_ru, :title_en, :raw_ru, :raw_en, :markup_ru, :markup_en, :image_path, :news_date)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'title_ru'   => $data['news_title_ru'] ?? '',
        'title_en'   => $data['news_title_en'] ?? '',
        'raw_ru'     => $data['raw_text_ru'] ?? null,
        'raw_en'     => $data['raw_text_en'] ?? null,
        'markup_ru'  => $data['markup_ru'] ?? null,
        'markup_en'  => $data['markup_en'] ?? null,
        'image_path' => $data['image_path'] ?? '',
        'news_date'  => $data['news_date'] ?? null,
    ]);

    return (int)$pdo->lastInsertId();
}

function updatePromo(int $id, array $data): bool
{
    global $pdo;

    $sql = "UPDATE promo_list
            SET news_title_ru = :title_ru,
                news_title_en = :title_en,
                raw_text_ru   = :raw_ru,
                raw_text_en   = :raw_en,
                markup_ru     = :markup_ru,
                markup_en     = :markup_en,
                image_path    = :image_path,
                news_date     = :news_date
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'title_ru'   => $data['news_title_ru'] ?? '',
        'title_en'   => $data['news_title_en'] ?? '',
        'raw_ru'     => $data['raw_text_ru'] ?? null,
        'raw_en'     => $data['raw_text_en'] ?? null,
        'markup_ru'  => $data['markup_ru'] ?? null,
        'markup_en'  => $data['markup_en'] ?? null,
        'image_path' => $data['image_path'] ?? '',
        'news_date'  => $data['news_date'] ?? null,
        'id'         => $id,
    ]);
}

function deletePromoById(int $id): bool
{
    global $pdo;

    $stmt = $pdo->prepare('DELETE FROM promo_list WHERE id = :id');
    return $stmt->execute(['id' => $id]);
}

function promoImagePublicPath(int $promoId): string
{
    return '/userfiles/img/promo/promo' . $promoId . '.jpg';
}

function savePromoImage(array $file, int $promoId): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmpName = $file['tmp_name'] ?? null;
    if (!$tmpName || !is_readable($tmpName)) {
        return null;
    }

    $imageInfo = getimagesize($tmpName);
    if ($imageInfo === false) {
        return null;
    }

    [$width, $height] = $imageInfo;
    $mime = $imageInfo['mime'] ?? '';

    if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
        return null;
    }

    $source = $mime === 'image/png'
        ? imagecreatefrompng($tmpName)
        : imagecreatefromjpeg($tmpName);

    if (!$source) {
        return null;
    }

    $targetWidth  = 290;
    $targetHeight = 225;
    $targetRatio  = $targetWidth / $targetHeight;
    $sourceRatio  = $width / $height;

    if ($sourceRatio > $targetRatio) {
        $cropHeight = $height;
        $cropWidth  = (int)round($height * $targetRatio);
        $srcX       = (int)floor(($width - $cropWidth) / 2);
        $srcY       = 0;
    } else {
        $cropWidth  = $width;
        $cropHeight = (int)round($width / $targetRatio);
        $srcX       = 0;
        $srcY       = (int)floor(($height - $cropHeight) / 2);
    }

    $destination = imagecreatetruecolor($targetWidth, $targetHeight);
    imagecopyresampled(
        $destination,
        $source,
        0,
        0,
        $srcX,
        $srcY,
        $targetWidth,
        $targetHeight,
        $cropWidth,
        $cropHeight
    );

    $publicPath = promoImagePublicPath($promoId);
    $rootPath   = rtrim($_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2), '/');
    $fullPath   = $rootPath . $publicPath;

    $dir = dirname($fullPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    imagejpeg($destination, $fullPath, 90);

    imagedestroy($source);
    imagedestroy($destination);

    return $publicPath;
}

function generatePromoMarkup(string $locale, string $sourceText): ?string
{
    $apiKey = $_ENV['OPENAI_API_KEY'] ?? '';
    if ($apiKey === '' || trim($sourceText) === '') {
        return null;
    }

    $model = $_ENV['OPENAI_MODEL'] ?? 'gpt-4o-mini';

    $system = 'You are a careful copywriter who converts plain text promo descriptions into well-structured HTML blocks for the ETFRIX cabinet. Keep links and numbers, use <p>, <ul>, <li>, <b>, <h6>. Wrap the textual part only (without outer <div class="tabs-3_promo">) inside a <div class="tabs-3_rightDescription">. Keep language as provided (ru or en). Add line breaks with separate <p> blocks, not <br> except where needed inside bullet items. Do not invent content.';

    $prompt = "Locale: {$locale}\n" .
        "Return HTML that fits inside the 'tabs-3_right' block with classes used in the promo example (promo-intro, promo-period, promo-desc)." .
        " Preserve bullet structure and headings from the text. Source text:\n" . trim($sourceText);

    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.2,
        'max_tokens' => 800,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_TIMEOUT        => 60,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    if (!is_string($response)) {
        return null;
    }

    $decoded = json_decode($response, true);
    if (!isset($decoded['choices'][0]['message']['content'])) {
        return null;
    }

    return trim($decoded['choices'][0]['message']['content']);
}

/**
 * Возвращает одну новость по её ID.
 *
 * @param int $id — ID записи в таблице news
 * @return array|null — ассоциативный массив полей новости или null, если не найдена
 */
function getOnePromoById(int $id): ?array {
    global $pdo;

    $sql  = "SELECT * FROM promo_list WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row !== false ? $row : null;
}


/**
 * Возвращает текст страницы по имени.
 *
 * @param string $name  — ключ записи в таблице text
 * @return string|null  — значение поля text_value или null, если запись не найдена
 */
function getPageTextByName(string $name): ?string {
    global $pdo;

    $sql  = "SELECT text_value FROM text WHERE name = :name LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name]);

    $value = $stmt->fetchColumn();
    return $value !== false ? $value : null;
}

/**
 * Обновляет текст страницы по имени.
 *
 * @param string $text  — новый текст (HTML-спецсимволы экранируются)
 * @param string $name  — ключ записи в таблице text
 * @return bool         — true при успешном обновлении, false при ошибке
 */
function setPageTextByName(string $text, string $name): bool {
    global $pdo;

    // Экранируем HTML-символы (аналог htmlspecialchars)
    $textConverted = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $sql  = "UPDATE text
             SET text_value = :text
             WHERE name = :name";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        'text' => $textConverted,
        'name' => $name
    ]);
}


function getRefCode(): string
{
    if (isset($_GET['code']) && preg_match('/^[A-Za-z0-9_-]{1,64}$/', $_GET['code'])) {
        return '?code=' . $_GET['code'];
    } else
        return '';
}

function checkTgActions($action): bool
{
    $validActions = array(
        'tg-accept-input',
        'tg-deny-input',
        'tg-block-input',
        'tg-accept-output',
        'tg-deny-output',
        'tg-block-output'
    );

    return in_array($action, $validActions);
}

//sendtgmessage
function withdrawTgMessage($name, $tgInfo, $amountUsd, $method, $wallet, $outputId, $refUser = null): string
{
    $acceptLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-accept-output&id=" . $outputId;
    $denyLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-deny-output&id=" . $outputId;
    $blockLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-block-output&id=" . $outputId;

    $message = "-----------=ВЫВОД=----------
<b>Заявка на ВЫВОД средств на ". $_ENV['CLEAR_URL'] ."</b>
Пользователь: $name
Telegram: $tgInfo
Сумма: $amountUsd
Метод вывода: $method
Кошелек: <code>$wallet</code>
--------------------------------
<a href='$acceptLink'>✅ ВЫВЕСТИ ✅</a>
--------------------------------
<a href='$denyLink'>🚫 ОТМЕНИТЬ 🚫</a>
--------------------------------
<a href='$blockLink'>🔒 ЗАБЛОКИРОВАТЬ 🔒</a>
--------------------------------";

    if ($refUser !== null) {
        $message .= "\n<b>Реф: </b>" . $refUser['user_name'] . " " . $refUser['sur_name'] . "(" . $refUser['email'] . ")";
    }
    return $message;
}

function topUpTgMessage($name, $amountUsd, $method, $amountCrypto, $inputId, $refUser = null): string
{
    $acceptLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-accept-input&id=" . $inputId;
    $denyLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-deny-input&id=" . $inputId;
    $blockLink = "https://" . $_ENV['CLEAR_URL'] . "/admin/tg-confirm?action=tg-block-input&id=" . $inputId;

    $message = "+++++++++++=ВВОД=+++++++++++
<b>Заявка на ВВОД средств на ". $_ENV['CLEAR_URL'] ."</b>
Пользователь: $name
Сумма в $: $amountUsd
Метод пополнения: $method
Сумма в крипте: $amountCrypto
--------------------------------
<a href='$acceptLink'>✅ РАЗРЕШИТЬ ВВОД ✅</a>
--------------------------------
<a href='$denyLink'>🚫 ОТМЕНИТЬ 🚫</a>
--------------------------------
<a href='$blockLink'>🔒 ЗАБЛОКИРОВАТЬ 🔒</a>
--------------------------------";

    if ($refUser !== null) {
        $message .= "\n<b>Реф: </b>" . $refUser['user_name'] . " " . $refUser['sur_name'] . "(" . $refUser['email'] . ")";
    }
    return $message;
}

function viewTgMessage($name, $amountUsd, $method): string
{
    return "👀 <b>" . $_ENV['CLEAR_URL'] ."</b> 👀
<b>$name</b> выбрал пополнение в <b>$method</b> 
на сумму: $amountUsd$";
}

// Функция для отправки сообщения в Telegram.
function sendTgMessage($message) {
    // Получаем токен бота
    $botToken = $_ENV['TG_BOTTOKEN'];

    // Получаем идентификаторы чатов
    $chatIds = [$_ENV['TG_CHATID1'], $_ENV['TG_CHATID2']];

    // URL для отправки сообщений
    $apiUrl = "https://api.telegram.org/bot{$botToken}/sendMessage";

    // Общие данные для отправки
    $data = [
        'text' => $message,
        'parse_mode' => 'html'
    ];

    $results = [];

    foreach ($chatIds as $chatId) {
        $data['chat_id'] = $chatId;

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $results[] = $result;
    }

    return $results;
}

function checkSession(): void
{
    if ($_ENV['DEV_MOCK'] === 'true' && !isset($_COOKIE['dev'])) {
        include $_SERVER['DOCUMENT_ROOT'] . '/../engine/mocking/mock.php';
        exit;
    }

    if (isset($_SESSION['LAST_ACTIVITY']) &&
        (time() - $_SESSION['LAST_ACTIVITY'] > 30 * 60)) {
        session_unset();
        session_destroy();
    }
    $_SESSION['LAST_ACTIVITY'] = time();


    /* --- ПРОВЕРКА АВТОРИЗАЦИИ + РЕФ-ШЛЮЗ --- */
    if (empty($_SESSION['user_id'])) {

        // 1) Нормализуем путь
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $path = rtrim($path, '/'); // '/ru/' -> '/ru'

        // 2) Разрешённые публичные страницы по реф-коду (RU)
        $refGatedPaths = [
            '/ru',
            '/ru/about',
            '/ru/tokenization',
            '/ru/affiliate',
            '/en',
            '/en/about',
            '/en/tokenization',
            '/en/affiliate',
        ];

        // 3) Есть ли валидный code в текущем запросе?
        $hasReferralCode = false;

        // 3a) Прямой ?code=... (разрешим 1..64 символов [A-Za-z0-9_-])
        if (isset($_GET['code'])) {
            $hasReferralCode = true;
        } else {
            // 3b) Или ?ref / ?referral = https://etfrix.org/referral?code=...
            foreach (['ref', 'referral'] as $param) {
                if (!empty($_GET[$param])) {
                    $url = urldecode($_GET[$param]);
                    $p   = parse_url($url);
                    if (!empty($p['host']) &&
                        strtolower($p['host']) === 'etfrix.com' &&
                        ($p['path'] ?? '') === '/referral' &&
                        !empty($p['query'])) {
                        parse_str($p['query'], $q);
                        if (!empty($q['code'])) {
                            $hasReferralCode = true;
                            break;
                        }
                    }
                }
            }
        }

        // 4) Если страница из списка и код есть — пускаем без редиректа
        if (in_array($path, $refGatedPaths, true) && $hasReferralCode) {
            return;
        }

        // 5) Иначе — как раньше
        header('Location: /_session');
        exit;
    }
}

function getClientIp(): string
{
    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            return explode(',', $_SERVER[$key])[0];
        }
    }
    return '0.0.0.0';
}

function isMobile(): bool
{
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    return str_contains($ua, 'iphone') || str_contains($ua, 'android');
}

/* ---------- USERS ---------- */

/**
 * Получить пользователя по email
 */
function getCurrentUserByEmail(string $email): ?array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() ?: null;
}

/**
 * Получить пользователя по uid
 */
function getUserById(int $id): ?array
{
    global $pdo;

    $stmt = $pdo->prepare(
        'SELECT u.*, COALESCE(ua.RIXCOIN, 0) AS roulette_coin
           FROM users u
           LEFT JOIN user_altcoins ua ON ua.user_id = u.uid
          WHERE u.uid = :uid
          LIMIT 1'
    );
    $stmt->execute(['uid' => $id]);
    return $stmt->fetch() ?: null;
}

function getTeamById(int $id): ?array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM teams WHERE team_id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

function getRegionById(int $id): ?array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM regions WHERE region_id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

/**
 * Получить пользователя по хэшу (cookie token)
 * Если у пользователя нет кошелька альткоинов — создает его с бонусом 300 RIXCOIN.
 */
function getUserByHash(string $hash): ?array
{
    global $pdo;

    // 1. Выбираем пользователя и ID записи альткоинов (чтобы проверить существование)
    $stmt = $pdo->prepare(
        'SELECT u.*, ua.id AS alt_row_id, COALESCE(ua.RIXCOIN, 0) AS roulette_coin
           FROM users u
           LEFT JOIN user_altcoins ua ON ua.user_id = u.uid
          WHERE u.activation = :hash
          LIMIT 1'
    );
    $stmt->execute(['hash' => $hash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если пользователь не найден — выходим
    if (!$user) {
        return null;
    }

    // 2. Проверяем: если alt_row_id пустой, значит записи в user_altcoins нет
    if (empty($user['alt_row_id'])) {
        // Создаем запись с приветственным бонусом 300
        // Используем INSERT IGNORE на случай гонки запросов (если вы добавили уникальный индекс)
        $insert = $pdo->prepare("INSERT IGNORE INTO user_altcoins (user_id, RIXCOIN) VALUES (:uid, 300)");
        $insert->execute(['uid' => $user['uid']]);

        // Обновляем данные в памяти, чтобы пользователь сразу увидел 300 монет, не обновляя страницу
        $user['roulette_coin'] = 300;

        // (Опционально) Можно перезапросить alt_row_id, но это не обязательно для логики
    }

    return $user;
}

/**
 * Получить пользователя по uid
 */
function getUserDealsByDealId(int $id): ?array
{
    global $pdo;

    $stmt = $pdo->prepare('SELECT * FROM user_deals WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

/* ---------- REGIONS ---------- */

/**
 * Вернёт список доступных регионов
 * [
 *   ['region_id' => 1, 'region_name' => 'РЕГИОН США'],
 *   ...
 * ]
 */
function getAllRegions(): array
{
    global $pdo;

    // небольшой кеш, чтобы не дёргать БД несколько раз за запрос
    static $cache = null;
    if ($cache === null) {
        $stmt  = $pdo->query('SELECT region_id, region_name FROM regions ORDER BY region_id');
        $cache = $stmt->fetchAll();
    }
    return $cache;
}

/* ---------- DEALS ---------- */

/**
 * Вернёт до трёх продуктов ('Small', 'Medium', 'Large') выбранной
 * команды и региона в правильном порядке.
 *
 * @return array<string>  например: ['ProShares BITO', 'Valkyrie BTF', 'VanEck XBTF']
 */
function getProductsForRegion(int $teamId, int $regionId): array
{
    global $pdo;

    $sql = "SELECT product
              FROM deals
             WHERE team_id  = :team
               AND region_id = :region
          ORDER BY FIELD(deal_size,'Small','Medium','Large')
             LIMIT 3";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['team' => $teamId, 'region' => $regionId]);

    return array_column($stmt->fetchAll(), 'product');
}

/**
 * Собирает сделки текущей команды, группируя по region_id.
 * Для Small/Medium/Large кладём объект, а для Flash — МАССИВ,
 * чтобы можно было вывести несколько кнопок в модалке.
 */
function getDealsByRegion(int $teamId): array
{
    global $pdo;

    // Берём язык из куки (en по умолчанию) и маппим на колонку
    $lang = strtolower($_COOKIE['lang'] ?? 'en');
    $colMap = [
        'en' => 'config_note_en',
        'ru' => 'config_note',    // русская версия в базовой колонке
        'cn' => 'config_note_cn',
        'ar' => 'config_note_ar',
    ];
    $col = $colMap[$lang] ?? $colMap['en'];

    // Берём только нужные поля + локализованную заметку
    $sql = "
        SELECT
            d.deal_id, d.region_id, d.deal_size, d.product,
            d.amount_min, d.amount_max, d.term_days,
            d.rate_without_RIX, d.rate_without_RIX_min, 
            d.rate_without_RIX_max, d.need_confirm,
            COALESCE(
                NULLIF(d.`$col`, ''),            -- выбранный язык
                NULLIF(d.`config_note_en`, ''),  -- фолбэк на EN
                d.`config_note`                  -- финальный фолбэк (RU/база)
            ) AS config_note
        FROM deals d
        WHERE d.team_id = :team
        ORDER BY d.region_id, d.deal_size, d.deal_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['team' => $teamId]);

    $out = [];
    foreach ($stmt as $row) {
        $r = (int)$row['region_id']; // удобный псевдоним/ключ

        // полный набор полей, который ждёт front-end
        $cfg = [
            'deal_id'              => (int)$row['deal_id'],
            'product'              => $row['product'],
            'amount_min'           => (int)$row['amount_min'],
            'amount_max'           => (int)$row['amount_max'],
            'term_days'            => (int)$row['term_days'],
            'rate_without_RIX'     => (float)$row['rate_without_RIX'],
            'rate_without_RIX_min' => (float)$row['rate_without_RIX_min'],
            'rate_without_RIX_max' => (float)$row['rate_without_RIX_max'],
            'need_confirm'         => (int)$row['need_confirm'],
            'config_note'          => $row['config_note'] ?? '',
        ];

        if ($row['deal_size'] === 'Flash') {
            $out[$r]['Flash'][] = $cfg;       // несколько Flash-конфигов
        } else {
            $out[$r][$row['deal_size']] = $cfg; // один конфиг на размер
        }
    }
    return $out;
}


function getDealsByRegionSimple(int $teamId): array
{
    global $pdo;

    $sql = "SELECT deal_id, product FROM deals WHERE team_id = :team";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['team' => $teamId]);

    return $stmt->fetchAll();
}


/**
 * Вернёт сделки конкретного пользователя.
 *
 * @param int         $userId   uid из таблицы users
 * @param string|null $status   'active' | 'completed' | 'cancelled' | null = все
 * @param bool        $onlyNew  true  — взять только непросмотренные (is_new = 1)
 *
 * @return array<array{
 *     user_deal_id:int, region_id:int, deal_size:string, product:string,
 *     principal:float,  accrued_amount:float, start_date:string, end_date:string,
 *     status:string,    is_new:int
 * }>
 */
function getUserDeals(int $userId, ?string $status = null, bool $onlyNew = false): array
{
    global $pdo;

    // Язык из cookie (en по умолчанию)
    $lang = strtolower($_COOKIE['lang'] ?? 'en');

    // Белый список колонок по языкам
    $colMap = [
        'en' => 'config_note_en',
        'ru' => 'config_note',     // русская версия хранится в базовой колонке
        'cn' => 'config_note_cn',
        'ar' => 'config_note_ar',
    ];
    $col = $colMap[$lang] ?? $colMap['en'];


    $sql = "
        SELECT 
            ud.id AS user_deal_id,
            ud.principal, ud.accrued_amount,
            ud.start_date, ud.end_date,
            ud.status, ud.is_new, ud.is_closed,
            d.region_id, d.deal_size, d.product,
            COALESCE(
                NULLIF(d.`$col`, ''),            -- выбраный язык
                NULLIF(d.`config_note_en`, ''),  -- фолбэк на EN
                d.`config_note`                  -- финальный фолбэк (RU/база)
            ) AS config_note
        FROM user_deals ud
        JOIN deals d ON d.deal_id = ud.deal_id
        WHERE ud.user_id = :uid";

    $params = ['uid' => $userId];

    if ($status !== null) {
        $sql      .= " AND ud.status = :st";
        $params['st'] = $status;
    }
    if ($onlyNew) {
        $sql .= " AND ud.is_new = 1";
    }

    $sql .= " ORDER BY ud.start_date DESC";
    $stmt  = $pdo->prepare($sql);
    $stmt->execute($params);

    $out = [];
    foreach ($stmt as $row) {
        $out[] = [
            'user_deal_id'   => (int)$row['user_deal_id'],
            'region_id'      => (int)$row['region_id'],
            'deal_size'      => $row['deal_size'],
            'product'        => $row['product'],
            'principal'      => (float)$row['principal'],
            'accrued_amount' => (float)$row['accrued_amount'],
            'start_date'     => $row['start_date'],
            'end_date'       => $row['end_date'],
            'status'         => $row['status'],
            'is_new'         => (int)$row['is_new'],
            'is_closed'      => (int)$row['is_closed'],
            'config_note'    => $row['config_note'] ?? '',
        ];
    }
    return $out;
}


/**
 * Возвращает массив суточного дохода по каждому активу (user_deal_id => amount) за указанный день.
 *
 * @param int         $userId  ID пользователя (users.uid).
 * @param string|null $date    Дата в формате 'Y-m-d'. По умолчанию — сегодня.
 * @return array               [ user_deal_id => '0.00', … ]
 */
function getUserDailyIncomeByDeal(int $userId, ?string $date = null): array
{
    global $pdo;

    $date = $date ?: date('Y-m-d');
    $sql = <<<SQL
SELECT a.user_deal_id, a.amount
  FROM user_deal_accruals AS a
  JOIN user_deals        AS ud ON ud.id = a.user_deal_id
 WHERE ud.user_id     = :user_id
   AND ud.status      = 'active'
   AND a.accrual_date = :date
SQL;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'date'    => $date,
    ]);

    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // приводим к формату «0.00»
        $result[(int)$row['user_deal_id']] = number_format((float)$row['amount'], 1, '.', ' ');
    }
    return $result;
}

/**
 * Возвращает суммарный суточный доход по всем активам за указанный день.
 *
 * @param int         $userId  ID пользователя (users.uid).
 * @param string|null $date    Дата в формате 'Y-m-d'. По умолчанию — сегодня.
 * @return string              Сумма «0.00».
 */
function getUserDailyIncomeTotal(int $userId, ?string $date = null): string
{
    global $pdo;

    $date = $date ?: date('Y-m-d');
    $sql = <<<SQL
SELECT SUM(a.amount) AS total
  FROM user_deal_accruals AS a
  JOIN user_deals        AS ud ON ud.id = a.user_deal_id
 WHERE ud.user_id     = :user_id
   AND ud.status      = 'active'
   AND a.accrual_date = :date
SQL;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'date'    => $date,
    ]);

    $sum = (float)$stmt->fetchColumn();
    return number_format($sum, 1, '.', ' ');
}

/**
 * Квартальный доход по день Х (по умолчанию — сегодня).
 * Суммируем только начисления, дата которых не позже $date.
 */
function getUserQuarterIncomeTotal(int $userId, ?string $date = null): string
{
    global $pdo;

    $dt     = new DateTime($date ?: 'now', new DateTimeZone('UTC'));
    $today  = $dt->format('Y-m-d');

    // границы календарного квартала
    $year   = (int)$dt->format('Y');
    $month  = (int)$dt->format('n');
    $qStartMonth = (int)(floor(($month - 1) / 3) * 3 + 1);   // 1,4,7,10
    $qStart = (new DateTimeImmutable("$year-$qStartMonth-01", new DateTimeZone('UTC')))
        ->format('Y-m-d');

    $sql = <<<SQL
            SELECT SUM(a.amount) AS total
              FROM user_deal_accruals AS a
              JOIN user_deals        AS ud ON ud.id = a.user_deal_id
             WHERE ud.user_id      = :user_id
               AND ud.status      <> 'cancelled'
               AND a.accrual_date BETWEEN :start AND :today      -- ← до сегодняшнего дня
SQL;
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'start'   => $qStart,
        'today'   => $today,
    ]);

    return number_format((float)$stmt->fetchColumn(), 1, '.', ' ');
}

/**
 * % «сумма active principal ➜ сумма дневного дохода».
 *
 *   percent = (Σ principal_active / Σ daily_income) * 100
 *
 * @param array $allDeals          Массив сделок (print_arr($allDeals))
 * @param array $dailyIncomeByDeal Ассоц. массив [deal_id => income] за день
 * @return string                  Готовое число формата "0.00"
 */
function getAssetsToDailyIncomePercent(array $allDeals, array $dailyIncomeByDeal): string
{
    /* 1. Σ principal всех активных сделок -------------------------------- */
    $principalSum = 0.0;
    foreach ($allDeals as $deal) {
        if (isset($deal['status']) && $deal['status'] === 'active') {
            $principalSum += (float) $deal['principal'];
        }
    }

    /* 2. Σ дневного дохода ------------------------------------------------- */
    //  Если массив пустой, array_sum вернёт 0
    $incomeSum = array_sum($dailyIncomeByDeal);

    /* 3. Защита от деления на ноль ---------------------------------------- */
    if ($principalSum == 0.0 || $incomeSum == 0.0) {
        return '0.00';
    }

    /* 4. Расчёт и форматирование ------------------------------------------ */
    $percent = ($incomeSum / $principalSum) * 100;
    return number_format($percent, 2, '.', ' ');
}

/**
 * Возвращает данные для 14-дневного графика «% доходности».
 *
 * ─ principal  — суммарные вложения пользователя (Σ principal всех его сделок,
 *                кроме отменённых) — нужна, чтобы на фронте посчитать %
 * ─ dates[]    — массив из 14 дат в формате d.m  (13 дней назад … сегодня)
 * ─ income[]   — массив из 14 строк «0.00» ― суточный доход за каждую дату
 *
 * @param int    $userId  users.uid
 * @param string $today   'Y-m-d' (можно подменять «виртуальной» датой в dev)
 *
 * @return array{principal:string, dates:array<int,string>, income:array<int,string>}
 */
function getUserDataForPercentChart(int $userId, string $today): array
{
    global $pdo;

    /* 1. Сумма principal всех (не отменённых) сделок пользователя */
    $stmt = $pdo->prepare("
        SELECT SUM(principal) AS total
          FROM user_deals
         WHERE user_id = :uid
           AND status  <> 'cancelled'
    ");
    $stmt->execute(['uid' => $userId]);
    $principal = (float)$stmt->fetchColumn();          // число! без format()

    /* 2. Суммируем начисления за 14-дневный диапазон */
    $start = (new DateTimeImmutable($today, new DateTimeZone('UTC')))
        ->modify('-13 days')
        ->format('Y-m-d');

    $sql = "
        SELECT a.accrual_date, SUM(a.amount) AS day_sum
          FROM user_deal_accruals a
          JOIN user_deals        ud ON ud.id = a.user_deal_id
         WHERE ud.user_id = :uid
           AND ud.status <> 'cancelled'
           AND a.accrual_date BETWEEN :start AND :today
         GROUP BY a.accrual_date
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'uid'   => $userId,
        'start' => $start,
        'today' => $today,
    ]);

    $byDate = [];
    foreach ($stmt as $row) {
        $byDate[$row['accrual_date']] = (float)$row['day_sum'];
    }

    /* 3. Формируем 14-дневный массив процентов */
    $income = [];
    for ($i = 13; $i >= 0; $i--) {
        $dObj = (new DateTimeImmutable($today, new DateTimeZone('UTC')))
            ->modify("-{$i} days");
        $key   = $dObj->format('Y-m-d');

        $dailyAmount = $byDate[$key] ?? 0.0;                 // начислено за день
        $percent     = $principal > 0
            ? ($dailyAmount / $principal) * 100
            : 0.0;

        // сохраняем с двумя знаками после запятой
        $income[] = $percent;
    }

    /* 4. Возвращаем готовый массив процентов (14 элементов) */
    return $income;
}

/**
 * Возвращает массив из 14 суточных доходов команды
 * в порядке 13 дней назад → сегодня.
 *
 * @return array<int,float>   14 чисел, например [0,0,1.5,…,3.2]
 */
/**
 * Возвращает массив из 14 суточных доходов команды
 * в порядке 13 дней назад → сегодня.
 *
 * @param int $userId
 * @return float[]  14 чисел, например [0,0,1.5,…,3.2]
 */
function getTeamIncomeChartData(int $userId): array
{
    global $pdo;

    // 1. Подготовим и выполним запрос
    $sql = "
        SELECT d0, d1, d2, d3, d4, d5, d6,
               d7, d8, d9, d10, d11, d12, d13
          FROM team_income_roll
         WHERE user_id = :uid
         LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $userId]);

    // 2. Забираем строку как числовой массив
    $row = $stmt->fetch(PDO::FETCH_NUM);

    // 3. Если строка отсутствует — вернём 14 нулей
    if ( ! $row ) {
        return array_fill(0, 14, 0.0);
    }

    // 4. Приведём к float и перевернём порядок (d13→d0 → [0]=13 дней назад)
    $numbers = array_map('floatval', $row);
    return array_reverse($numbers);
}



/**
 * Оставляет в $dealsByRegion только Flash-сделки.
 *
 * @param array<int, mixed> $dealsByRegion
 *   — исходный массив сделок по регионам. Формат значения может быть либо
 *     1) ['Small'=>cfg, 'Medium'=>cfg, 'Flash'=>cfg, …], либо
 *     2) [ 0=>['deal_size'=>'Small',…], 1=>['deal_size'=>'Flash',…], … ]
 * @return array<int, array<int,array>>
 *   — в результате для каждого region_id массив всех Flash-сделок (каждая
 *     сделка — ассоц. массив с её параметрами).
 */
/**
 * Оставляет в $dealsByRegion только Flash-сделки в удобном виде:
 * [ region_id => [ dealCfg1, dealCfg2, … ], … ]
 *
 * @param array<int, mixed> $dealsByRegion
 * @return array<int, array<int, array>>
 */
function filterFlashDeals(array $dealsByRegion): array
{
    $flashOnly = [];

    foreach ($dealsByRegion as $regionId => $deals) {
        // если для этого региона есть ключ 'Flash' — это уже массив с одной или несколькими сделками
        if (!empty($deals['Flash']) && is_array($deals['Flash'])) {
            // просто копируем его "на уровень" $flashOnly[$regionId]
            $flashOnly[$regionId] = $deals['Flash'];
        }
    }

    return $flashOnly;
}

/**
 * Вернуть актуальные котировки активов.
 *
 * @param int $limit        Максимум строк (0 = без лимита)
 * @return array<array{symbol:string,price:float,percent_change:float}>
 */
function getAssetQuotes(int $limit = 0): array
{
    global $pdo;

    $sql = "
        SELECT symbol,
               price,
               percent_change
          FROM asset_quotes
      ORDER BY quote_time DESC
    ";

    if ($limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Вернуть «человеческое» название актива по его символу.
 *
 * @param string $symbol
 * @return string
 */
function assetTitle(string $symbol): string
{
    // Убираем префикс биржи (например, BINANCE:) и суффикс пары (например, USDT)
    $parts = explode(':', $symbol);
    $pair  = end($parts);
    $base  = preg_replace('/(USDT|USD|BTC|ETH)$/i', '', $pair);

    // Полный словарь соответствий
    static $map = [
        // Криптовалюты
        'BTC'   => 'Bitcoin',
        'ETH'   => 'Ethereum',
        'LTC'   => 'Litecoin',
        'SOL'   => 'Solana',
        'TON'   => 'Toncoin',
        'USDC'  => 'USD Coin',
        'XRP'   => 'Ripple',

        // ETF и фонды
        'ARKF'  => 'ARK Fintech Innovation ETF',
        'ARKK'  => 'ARK Innovation ETF',
        'BITO'  => 'ProShares Bitcoin Strategy ETF',
        'BLOK'  => 'Amplify Transformational Data Sharing ETF',
        'GBTC'  => 'Grayscale Bitcoin Trust',
        'IBIT'  => 'iShares Bitcoin Trust',
        'IVV'   => 'iShares Core S&P 500 ETF',
        'QQQ'   => 'Invesco QQQ Trust',
        'SMH'   => 'VanEck Semiconductor ETF',
        'SPY'   => 'SPDR S&P 500 ETF Trust',
        'VOO'   => 'Vanguard S&P 500 ETF',
        'VTI'   => 'Vanguard Total Stock Market ETF',
        'VUG'   => 'Vanguard Growth ETF',
    ];

    $key = strtoupper($base);
    return $map[$key] ?? $symbol;
}

/**
 * Вернуть «чистый» тикер, без префиксов биржи и суффикса пары.
 *
 * @param string $symbol
 * @param bool   $withSeparator  если true, вернёт формат "LTC/USDT", иначе просто "LTCUSDT" или "LTC".
 * @return string
 */
function assetCleanSymbol(string $symbol, bool $withSeparator = false): string
{
    // Разделяем префикс
    $parts = explode(':', $symbol);
    $pair  = end($parts);                     // например "LTCUSDT"

    if (! $withSeparator) {
        // просто базу (LTC)
        return preg_replace('/(USDT|USD|BTC|ETH)$/i', '', $pair);
    }

    // формат с разделителем (LTC/USDT)
    if (preg_match('/^([A-Z]+)(USDT|USD|BTC|ETH)$/i', $pair, $m)) {
        return strtoupper($m[1]) . '/' . strtoupper($m[2]);
    }

    return strtoupper($pair);
}

/**
 * Кошелёк (выписки) с поддержкой 4 языков: en, ru, cn (zh-CN), ar.
 */
function getWalletStats(int $uid, string $lang = 'ru'): array
{
    global $pdo;

    $lang = strtolower($lang);
    if (!in_array($lang, ['en','ru','cn','ar'], true)) {
        $lang = 'en';
    }

    // Соответствия локалей и форматов дат (fallback, если Intl недоступен)
    $localeByLang = [
        'en' => 'en_US',
        'ru' => 'ru_RU',
        'cn' => 'zh_CN',
        'ar' => 'ar_EG',
    ];
    $datePatternByLang = [
        'en' => 'MM/dd/yyyy',
        'ru' => 'dd.MM.yyyy',
        'cn' => 'yyyy/MM/dd',
        'ar' => 'dd/MM/yyyy',
    ];

    // Переводы
    $statusTxtByLang = [
        'en' => [0 => 'Pending',   1 => 'Completed', 2 => 'Rejected'],
        'ru' => [0 => 'В ожидании', 1 => 'Завершено', 2 => 'Отклонено'],
        'cn' => [0 => '待处理',      1 => '已完成',     2 => '已拒绝'],
        'ar' => [0 => 'قيد الانتظار', 1 => 'مكتمل',   2 => 'مرفوض'],
    ];
    $actionTxtByLang = [
        'en' => ['in' => 'Deposit',   'out' => 'Withdrawal'],
        'ru' => ['in' => 'Поступление','out' => 'Вывод'],
        'cn' => ['in' => '充值',       'out' => '提现'],
        'ar' => ['in' => 'إيداع',      'out' => 'سحب'],
    ];
    $mapClass = [0 => 'text-td__yellow', 1 => 'text-td__green', 2 => 'text-td__red'];

    $locale   = $localeByLang[$lang];
    $datePat  = $datePatternByLang[$lang];
    $statusTx = $statusTxtByLang[$lang];
    $actionTx = $actionTxtByLang[$lang];

    // ====== форматтеры ======
    $fmtAmount = static function (float $v) use ($locale, $lang): string {
        if (class_exists('\NumberFormatter')) {
            $nf = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
            $nf->setAttribute(\NumberFormatter::FRACTION_DIGITS, 0);
            $num = $nf->format($v);
        } else {
            // Простой фолбэк
            // RU: пробелы, остальным — запятые
            $num = $lang === 'ru'
                ? number_format($v, 0, ',', ' ')
                : number_format($v, 0, '.', ',');
        }
        return $num . ' USDT';
    };

    $fmtDate = static function (string $isoDate) use ($locale, $datePat, $lang): string {
        $ts = strtotime($isoDate) ?: time();
        if (class_exists('\IntlDateFormatter')) {
            $df = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE);
            $df->setPattern($datePat);
            return $df->format($ts);
        }
        // Фолбэк
        $phpPat = [
                'en' => 'm/d/Y',
                'ru' => 'd.m.Y',
                'cn' => 'Y/m/d',
                'ar' => 'd/m/Y',
            ][$lang] ?? 'm/d/Y';
        return date($phpPat, $ts);
    };

    // ====== утилиты ======
    $shorten = static function (string $s): string {
        $len = mb_strlen($s, 'UTF-8');
        return $len > 12
            ? mb_substr($s, 0, 5, 'UTF-8') . '…' . mb_substr($s, -5, null, 'UTF-8')
            : $s;
    };

    $normalize = static function (array $row, string $action) use ($statusTx, $mapClass, $shorten, $fmtAmount, $fmtDate): array {
        $st = !empty($row['blocked']) ? 2 : (int)($row['status'] ?? 0);
        return [
            'date'         => $fmtDate((string)$row['date']),
            'action'       => $action,
            'amount'       => $fmtAmount((float)$row['amount_usd']),
            'source'       => $shorten((string)($row['src'] ?? '')),
            'status'       => $statusTx[$st]   ?? '—',
            'status_class' => $mapClass[$st]   ?? '',
        ];
    };

    // ====== Поступления ======
    $sql = "SELECT date, amount_usd, tx_hash AS src, status, blocked
            FROM inputs
            WHERE user_id = :uid
            ORDER BY date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['uid' => $uid]);
    $inputs = [];
    foreach ($stmt as $row) {
        $inputs[] = $normalize($row, $actionTx['in']);
    }

    // ====== Выводы ======
    $sql = "SELECT date, amount_usd, wallet AS src, status, blocked
            FROM outputs
            WHERE user_id = :uid
            ORDER BY date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['uid' => $uid]);
    $outputs = [];
    foreach ($stmt as $row) {
        $outputs[] = $normalize($row, $actionTx['out']);
    }

    return ['inputs' => $inputs, 'outputs' => $outputs];
}

function getAllInputs(): array
{
    global $pdo;

    $sql = "SELECT * FROM inputs ORDER BY date DESC";
    $stmt = $pdo->query($sql);
    // теперь каждая строка — ассоц. массив с ключами ['id','user_id',…]
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllOutputs(): array
{
    global $pdo;

    $sql = "SELECT * FROM outputs ORDER BY date DESC";
    $stmt = $pdo->query($sql);
    // теперь каждая строка — ассоц. массив с ключами ['id','user_id',…]
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllDeals(): array
{
    global $pdo;

    $sql = "SELECT * FROM deals";
    $stmt = $pdo->query($sql);
    // теперь каждая строка — ассоц. массив с ключами ['id','user_id',…]
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getInputById($id){
    global $pdo;

    // Использовать подготовленные выражения!
    $stmt = $pdo->prepare("SELECT * FROM inputs WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // вернёт просто ['id'=>5, 'user_id'=>2, …]
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getOutputById($id){
    global $pdo;

    // Использовать подготовленные выражения!
    $stmt = $pdo->prepare("SELECT * FROM outputs WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // вернёт просто ['id'=>5, 'user_id'=>2, …]
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function acceptInputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE inputs SET status = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function acceptOutputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE outputs SET status = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function denyInputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE inputs SET status = 2 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function denyOutputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE outputs SET status = 2 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function blockOutputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE outputs SET blocked = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function unblockOutputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE outputs SET blocked = 0 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function blockInputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE inputs SET blocked = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function unblockInputById($id){
    global $pdo;
    $stmt = $pdo->prepare("UPDATE inputs SET blocked = 0 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function setUserMoneyById($id, $amount){
    global $pdo;

    // Подготовка запроса с параметрами :amount и :id
    $stmt = $pdo->prepare("
        UPDATE users
        SET balance = ROUND(balance + :amount, 2)
        WHERE uid = :id
    ");

    // Выполнение запроса с привязкой параметров
    return $stmt->execute([
        'amount' => $amount,
        'id'     => $id
    ]);
}

function setUserRouletteCoinById($id, $amount){
    global $pdo;

    // Пытаемся вставить запись. Если она есть — обновляем баланс.
    // Это выполняется за 1 запрос вместо 2-х.
    $sql = "INSERT INTO user_altcoins (user_id, RIXCOIN) 
            VALUES (:id, :amount) 
            ON DUPLICATE KEY UPDATE RIXCOIN = ROUND(RIXCOIN + :amount, 2)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id'     => $id,
        'amount' => $amount
    ]);

    // Получаем актуальный баланс для возврата
    $stmt = $pdo->prepare('SELECT RIXCOIN FROM user_altcoins WHERE user_id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? (int)$row['RIXCOIN'] : null;
}


function setTeamMoneyById($id, $amount){
    global $pdo;

    // Подготовка запроса с параметрами :amount и :id
    $stmt = $pdo->prepare("
        UPDATE users
        SET balance_team = ROUND(balance_team + :amount, 2)
        WHERE uid = :id
    ");

    $res1 = $stmt->execute([
        ':amount' => $amount,
        ':id'     => $id,
    ]);

    // 2) Апсертим team_income_roll.d0
    $upsert = $pdo->prepare("
        INSERT INTO team_income_roll (user_id, d0)
        VALUES (:id, :amount)
        ON DUPLICATE KEY UPDATE
            d0 = ROUND(d0 + VALUES(d0), 2),
            updated_at = NOW()
    ");
    $res2 = $upsert->execute([
        ':id'     => $id,
        ':amount' => $amount,
    ]);

    return $res1 && $res2;
}



//$transaction_type
//[0] - admin accept input
// [1] - admin accept output
// [2] - automatic referral action
// [10 -> 3] - admin deny input
// [11 -> 4] - admin deny output and money returned to user
// [99 -> 5] - system error
// [6] - user bought active
// [7] - active started by user
// [8] - automatic active closed
// [9] - automatic active body back to user
// [10]- user create output and money was substracted
// [11]- admin changed user balance
// [12]- admin block user output
// [13]- admin unblock user output
// [14]- admin block user input
// [15]- admin unblock user input
function logTransaction($user_id, $ref_id, $amount_usd, $percent, $total_usd, $transaction_type){
    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO transactions
            (user_id, ref_id, amount_usd, percent, total_usd, date, type)
        VALUES
            (:user_id, :ref_id, :amount_usd, :percent, :total_usd, NOW(), :type)
    ");

    return $stmt->execute([
        'user_id'          => $user_id,
        'ref_id'           => $ref_id,
        'amount_usd'       => $amount_usd,
        'percent'          => $percent,
        'total_usd'        => $total_usd,
        'type'             => $transaction_type,
    ]);
}


function isActiveTab(string $path): bool
{
    // получаем текущий путь без query-string
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $current === $path;
}

/**
 * Форматирует число с заданным количеством знаков после запятой,
 * разделителем дробной части — точка, разделителем тысяч — пробел.
 *
 * @param float $value     — исходное число
 * @param int   $decimals  — кол-во знаков после точки (по умолчанию 1)
 * @return string
 */
function moneyFormat(float $value, int $decimals = 1): string
{
    return number_format($value, $decimals, '.', ' ');
}

function registrationEmailBody($activationUrl, $clearUrl){
    $path = $_SERVER['DOCUMENT_ROOT'];
//    if ($localization == "ru")
//        $template = file_get_contents($path . '/../engine/template/registration-template.html');
//    else
    $template = file_get_contents($path . '/../engine/template/registration-template.html');

    $template = str_replace('{{activationURL}}', $activationUrl , $template);
    $template = str_replace('{{clearURL}}', $clearUrl , $template);

    return $template;
}

/**
 * Вернёт полную запись пользователя, если activation-код существует,
 * иначе — null.
 *
 * @param PDO    $pdo  готовое подключение к БД
 * @param string $code значение из параметра ?code=...
 * @return array|null
 */
function findUserByActivation(string $code): ?array
{
    global $pdo;

    $sql = 'SELECT * FROM users WHERE activation = :code LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['code' => $code]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function getTariffLevelId(string $lvl): int {
    global $pdo;
    $lvl = trim($lvl);
    if ($lvl === '' || strlen($lvl) > 3) {
        return 0;
    }
    $stmt = $pdo->prepare('SELECT id FROM tariff_levels WHERE lvl = ? LIMIT 1');
    $stmt->execute([$lvl]);
    $id = $stmt->fetchColumn();
    return $id ? (int)$id : 0;
}

/**
 * Возвращает общую сумму amount_usd по активным записям inputs для заданного user_id
 *
 * @param int $id — идентификатор пользователя
 * @return float — сумма amount_usd (0, если записей нет)
 */
function getAmountInputsByUserId(int $id): float
{
    global $pdo;

    $sql = "
        SELECT COALESCE(SUM(amount_usd), 0) AS total
        FROM inputs
        WHERE user_id = :user_id
          AND status = 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (float) $row['total'];
}

/**
 * Возвращает общую сумму amount_usd по активным записям inputs для заданного user_id
 *
 * @param int $id — идентификатор пользователя
 * @return float — сумма amount_usd (0, если записей нет)
 */
function getAmountOutputsByUserId(int $id): float
{
    global $pdo;

    $sql = "
        SELECT COALESCE(SUM(amount_usd), 0) AS total
        FROM outputs
        WHERE user_id = :user_id
          AND status = 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $id]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (float) $row['total'];
}

/**
 * Возвращает массив пользователей, чьё поле `referral` равно заданному $uid.
 * Ключ массива — uid пользователя, значение — ассоциативный массив полей.
 *
 * @param int $uid — идентификатор пригласившего пользователя
 * @return array<int, array<string, mixed>>
 */
function getUserReferrals(int $uid): array
{
    global $pdo;

    $sql = "
        SELECT *
        FROM users
        WHERE referral = :uid
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':uid' => $uid]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];
    foreach ($rows as $row) {
        $result[(int)$row['uid']] = $row;
    }

    return $result;
}


/**
 * Подсчитывает общее количество рефералов во всех уровнях вложенности.
 *
 * @param array<int, array<int, mixed>> $recursiveUserReferrals  Многомерный массив,
 *        где каждый элемент — массив рефералов текущего уровня.
 * @return int  Общее число всех рефералов.
 */
function getRecursiveUserReferralsCount(array $recursiveUserReferrals): int
{
    // Суммируем размеры всех внутренних массивов
    return array_sum(
        array_map(
            fn($level) => is_array($level) ? count($level) : 0,
            $recursiveUserReferrals
        )
    );
}


/**
 * Возвращает рефералов пользователя по уровням (до трёх уровней по умолчанию).
 *
 * @param int $uid          — идентификатор пользователя, для которого собираем рефералов
 * @param int $maxDepth     — максимальная глубина уровней (1 = только прямые, 2 = +второй уровень и т.д.)
 * @return array<int, array<int, array<string,mixed>>>
 *     Массив, где ключ — уровень (0 = первые, 1 = вторые, 2 = третьи),
 *     значение — массив записей из таблицы users.
 */
function getRecursiveUserReferrals(int $uid, int $maxDepth = 3): array
{
    // для работы используем глобальную функцию getUserReferrals(),
    // которая возвращает массив вида [refUid => [поля...], ...]
    $allLevels = [];
    // Для первого уровня стартуем с исходного $uid
    $currentParents = [$uid];

    for ($level = 0; $level < $maxDepth; $level++) {
        $nextLevel = [];

        // Проходимся по каждому родителю текущего уровня
        foreach ($currentParents as $parentId) {
            $refs = getUserReferrals($parentId);
            if (!empty($refs)) {
                // приводим к списку записей и добавляем в следующий уровень
                $nextLevel = array_merge($nextLevel, array_values($refs));
            }
        }

        // Сохраняем результаты этого уровня
        $allLevels[$level] = $nextLevel;

        // Готовим список uid для следующей итерации
        $currentParents = array_map(
            fn(array $r) => (int)$r['uid'],
            $nextLevel
        );

        // Если на уровне нет рефералов — дальше идти некуда
        if (empty($currentParents)) {
            // Дополняем пустыми массивами, если нужно ровно $maxDepth уровней
            for ($l = $level + 1; $l < $maxDepth; $l++) {
                $allLevels[$l] = [];
            }
            break;
        }
    }

    return $allLevels;
}


/**
 * Возвращает все записи из таблицы user_actives для заданного user_id,
 * индексируя результат по полю id
 *
 * @param int $userId — идентификатор пользователя
 * @return array — массив записей, где ключ — id записи, значение — ассоциативный массив полей
 */
function getUserDealsByUserId(int $userId): array
{
    global $pdo;

    $sql = "
        SELECT *
        FROM user_deals
        WHERE user_id = :user_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];
    foreach ($rows as $row) {
        $result[$row['id']] = $row;
    }

    return $result;
}


/**
 * Подсчитывает количество рефералов, у которых есть активы.
 *
 * @param array<int, array<int, array{uid:int}>> $recursiveReferralsForUser
 * @return int
 */
function getActiveReferralsCount(array $recursiveReferralsForUser): int
{
    // Собираем все рефералы в один плоский массив
    $allReferrals = [];
    foreach ($recursiveReferralsForUser as $level) {
        if (is_array($level)) {
            $allReferrals = array_merge($allReferrals, $level);
        }
    }

    // Фильтруем тех, у кого есть активы, и считаем их
    $activeCount = 0;
    foreach ($allReferrals as $referral) {
        $uid = isset($referral['uid']) ? (int)$referral['uid'] : 0;
        if ($uid > 0 && !empty(getUserDealsByUserId($uid))) {
            $activeCount++;
        }
    }

    return $activeCount;
}

/**
 * Возвращает всех пользователей из таблицы `users`,
 * где ключ массива — uid пользователя, значение — ассоциативный массив полей.
 *
 * @return array<int, array<string, mixed>>
 */
function getAllUsers(): array
{
    global $pdo; // используем глобальный PDO

    // Выполняем простой запрос без параметров
    $stmt = $pdo->query("SELECT * FROM users");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Индексируем по uid
    $result = [];
    foreach ($rows as $row) {
        $uid = isset($row['uid']) ? (int)$row['uid'] : 0;
        if ($uid > 0) {
            $result[$uid] = $row;
        }
    }

    return $result;
}

/**
 * Возвращает все записи из таблицы `outputs` для заданного пользователя,
 * без сортировки по дате. Результат индексируется по полю `id` записи.
 *
 * @param int $uid — идентификатор пользователя
 * @return array<int, array<string, mixed>>
 */
function getCurrentUserOutputsNotOrderedByDate(int $uid): array
{
    global $pdo; // используем глобальный PDO

    $sql  = "SELECT * FROM outputs WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $uid]);

    $rows   = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($rows as $row) {
        $id = isset($row['id']) ? (int)$row['id'] : 0;
        if ($id > 0) {
            $result[$id] = $row;
        }
    }

    return $result;
}

/**
 * Возвращает все записи из таблицы `inputs` для заданного пользователя,
 * без сортировки по дате. Результат индексируется по полю `id` записи.
 *
 * @param int $uid — идентификатор пользователя
 * @return array<int, array<string, mixed>>
 */
function getCurrentUserInputsNotOrderedByDate(int $uid): array
{
    global $pdo; // используем глобальный PDO

    $sql  = "SELECT * FROM inputs WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $uid]);

    $rows   = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($rows as $row) {
        $id = isset($row['id']) ? (int)$row['id'] : 0;
        if ($id > 0) {
            $result[$id] = $row;
        }
    }

    return $result;
}

/**
 * Возвращает все транзакции из таблицы `transactions` для заданного пользователя,
 * где поле `ref_id` соответствует указанному userID. Результат индексируется по полю `id` транзакции.
 *
 * @param int $userID — идентификатор пользователя
 * @return array<int, array<string, mixed>>
 */
function getAllTransactionsForUser(int $userID): array
{
    global $pdo; // используем глобальный PDO

    $sql  = "SELECT * FROM transactions WHERE ref_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userID]);

    $rows   = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($rows as $row) {
        $id = isset($row['id']) ? (int)$row['id'] : 0;
        if ($id > 0) {
            $result[$id] = $row;
        }
    }

    return $result;
}

/**
 * Возвращает запись из таблицы user_verification для заданного user_id,
 * включая дату рождения и пути к файлам. Если записи нет — возвращает false.
 *
 * @param int $userId — идентификатор пользователя
 * @return array<string, mixed>|false
 */
function getUserVerificationByUserId(int $userId)
{
    global $pdo;

    $sql = "SELECT * 
            FROM user_verification 
            WHERE user_id = :user_id
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $userId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Возвращает данные сделки из таблицы `deals` по её идентификатору.
 *
 * @param int $dealId — идентификатор сделки (deal_id)
 * @return array<string, mixed>|false  Ассоциативный массив полей сделки или false, если не найдено
 */
function getDealById(int $dealId)
{
    global $pdo; // используем глобальный PDO

    $sql  = "SELECT *
             FROM deals
             WHERE deal_id = :deal_id
             LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':deal_id' => $dealId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Устанавливает для пользователя с данным uid значение email_status = 1.
 *
 * @param int $uid — идентификатор пользователя
 * @return bool — true, если запрос выполнился успешно (и хотя бы одна строка была затронута), иначе false
 */
function activateUserEmailStatus(int $uid): bool
{
    global $pdo;

    $sql = "UPDATE users
            SET email_status = '1'
            WHERE uid = :uid
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([':uid' => $uid]);

    // Проверяем, что запрос выполнился и хотя бы одна строка была обновлена
    return $success && $stmt->rowCount() > 0;
}


function setUserPassword(int $uid, string $pass): bool
{
    global $pdo;

    $sql = 'UPDATE users SET password = :pass WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'pass' => $pass,
        'uid' => $uid,
    ]);
}

function setUserReferral(int $uid, string $referral): bool
{
    global $pdo;

    $sql = 'UPDATE users SET referral = :referral WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'referral' => $referral,
        'uid' => $uid,
    ]);
}

function setUserNames(int $uid, string $user_name, string $sur_name): bool
{
    global $pdo;

    $sql = 'UPDATE users SET user_name = :user_name, sur_name = :sur_name WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'user_name' => $user_name,
        'sur_name' => $sur_name,
        'uid' => $uid,
    ]);
}

function setUserNamesAndPhones(int $uid, string $user_name, string $sur_name, string $phone, string $telegram): bool
{
    global $pdo;

    $sql = 'UPDATE users SET user_name = :user_name, sur_name = :sur_name, phone = :phone, telegram = :telegram WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'user_name' => $user_name,
        'sur_name' => $sur_name,
        'phone' => $phone,
        'telegram' => $telegram,
        'uid' => $uid,
    ]);
}

function setUserEmailAndStatus0(int $uid, string $email): bool
{
    global $pdo;

    $sql = 'UPDATE users SET email = :email, email_status = 0 WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'email' => $email,
        'uid' => $uid,
    ]);
}

function setUserEmail(int $uid, string $email): bool
{
    global $pdo;

    $sql = 'UPDATE users SET email = :email WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'email' => $email,
        'uid' => $uid,
    ]);
}

function setUserEmailStatus(int $uid, int $emailStatus): bool
{
    global $pdo;

    $sql = 'UPDATE users SET email_status = :email_status WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'email_status' => $emailStatus,
        'uid' => $uid,
    ]);
}

function setUserCabinetStatus(
    int $uid,
    string $status,
    int $active,
    string $rating,
    int $verified,
    string $createDate
): bool {
    global $pdo;

    $sql = 'UPDATE users 
            SET status = :status, 
                active = :active, 
                rating = :rating, 
                verified = :verified, 
                create_date = :create_date 
            WHERE uid = :uid';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'status'      => $status,
        'active'      => $active,
        'rating'      => $rating,
        'verified'    => $verified,
        'create_date' => $createDate,
        'uid'         => $uid,
    ]);
}

function setUserCabinetVirtualData(
    int $uid,
    int $v_virtual,
    int $v_active_partners,
    int $v_total_partners
): bool {
    global $pdo;

    $sql = 'UPDATE users 
            SET v_virtual = :v_virtual, 
                v_active_partners = :v_active_partners, 
                v_total_partners = :v_total_partners
            WHERE uid = :uid';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'v_virtual'         => $v_virtual,
        'v_active_partners' => $v_active_partners,
        'v_total_partners'  => $v_total_partners,
        'uid'               => $uid,
    ]);
}

function setBalanceById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET balance = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setBalanceTeamById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET balance_team = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setBalancePromoById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET balance_promo = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setTotalAccruedById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET total_accrued = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setTotalTeamAccruedById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET total_team_accrued = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setTotalPromoAccruedById(int $uid, float $amount): bool
{
    global $pdo;

    $sql = 'UPDATE users SET total_promo_accrued = :amount WHERE uid = :uid';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setRixCoinById(int $uid, float $amount): bool
{
    global $pdo;

    $stmt = $pdo->prepare(
        'INSERT INTO user_altcoins (user_id, RIXCOIN)
         VALUES (:uid, :amount)
         ON DUPLICATE KEY UPDATE RIXCOIN = :amount'
    );
    return $stmt->execute([
        'amount' => $amount,
        'uid'    => $uid,
    ]);
}

function setInputData(int $id, float $amountUsd, float $amountCrypto, string $method, string $date): bool
{
    global $pdo;

    $sql = 'UPDATE inputs 
            SET amount_usd = :amount_usd, 
                amount_crypto = :amount_crypto, 
                method = :method, 
                date = :date 
            WHERE id = :id';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount_usd'     => $amountUsd,
        'amount_crypto'  => $amountCrypto,
        'method'         => $method,
        'date'           => $date,
        'id'             => $id,
    ]);
}

function setOutputData(int $id, float $amountUsd, string $method, string $wallet, string $date): bool
{
    global $pdo;

    $sql = 'UPDATE outputs 
            SET amount_usd = :amount_usd, 
                method = :method, 
                wallet = :wallet, 
                date = :date 
            WHERE id = :id';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'amount_usd' => $amountUsd,
        'method'     => $method,
        'wallet'     => $wallet,
        'date'       => $date,
        'id'         => $id,
    ]);
}

function createNewInput(int $userId, float $amountUsd, float $amountCrypto, string $method, string $txHash = ''): int
{
    global $pdo;

    $sql = 'INSERT INTO inputs (user_id, amount_usd, amount_crypto, method, tx_hash, status, blocked)
            VALUES (:user_id, :amount_usd, :amount_crypto, :method, :tx_hash, 0, 0)';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id'       => $userId,
        'amount_usd'    => $amountUsd,
        'amount_crypto' => $amountCrypto,
        'method'        => $method,
        'tx_hash'       => $txHash,
    ]);

    return (int)$pdo->lastInsertId();
}

function createNewOutput(int $userId, float $amountUsd, string $wallet, string $method): int
{
    global $pdo;

    $sql = 'INSERT INTO outputs (user_id, amount_usd, wallet, method, status, blocked)
            VALUES (:user_id, :amount_usd, :wallet, :method, 0, 0)';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id'    => $userId,
        'amount_usd' => $amountUsd,
        'wallet'     => $wallet,
        'method'     => $method,
    ]);

    return (int)$pdo->lastInsertId();
}

function createNewDeal(
    int $userId,
    int $dealId,
    float $principal,
    float $accruedAmount = 0.0,
    ?string $lastAccrualOn = null,
    string $status = 'active'
): int {
    global $pdo;

    $sql = 'INSERT INTO user_deals (
                user_id, deal_id, principal, start_date, end_date,
                daily_target, daily_min, daily_max, accrued_amount,
                last_accrual_on, status, payout_mode
            ) VALUES (
                :user_id, :deal_id, :principal, NOW(), NOW(),
                :daily_target, :daily_min, :daily_max, :accrued_amount,
                :last_accrual_on, :status, :payout_mode
            )';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id'        => $userId,
        'deal_id'        => $dealId,
        'principal'      => $principal,
        'daily_target'   => 0,
        'daily_min'      => 0,
        'daily_max'      => 0,
        'accrued_amount' => $accruedAmount,
        'last_accrual_on'=> $lastAccrualOn,
        'status'         => $status,
        'payout_mode'    => 'end',
    ]);

    return (int)$pdo->lastInsertId();
}




function getCurrentDealById(int $dealId): ?array
{
    global $pdo;

    $sql = 'SELECT * FROM deals WHERE deal_id = :deal_id LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['deal_id' => $dealId]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function updateUserDealById(
    int $id,
    int $dealId,
    float $principal,
    string $startDate,
    string $endDate,
    float $accruedAmount,
    string $status
): bool {
    global $pdo;

    $sql = 'UPDATE user_deals
            SET principal = :principal,
                deal_id = :deal_id,
                start_date = :start_date,
                end_date = :end_date,
                accrued_amount = :accrued_amount,
                status = :status
            WHERE id = :id';

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'deal_id'          => $dealId,
        'principal'        => $principal,
        'start_date'       => $startDate,
        'end_date'         => $endDate,
        'accrued_amount'   => $accruedAmount,
        'status'           => $status,
        'id'               => $id
    ]);
}

function updateUserDealStatus(int $id, string $status): bool
{
    global $pdo;

    $sql = 'UPDATE user_deals SET status = :status WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'status' => $status,
        'id'     => $id,
    ]);
}

function deleteDealById(int $id): bool
{
    global $pdo;

    $sql = 'DELETE FROM user_deals WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

function deleteInputById(int $id): bool
{
    global $pdo;

    $sql = 'DELETE FROM inputs WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

function deleteOutputById(int $id): bool
{
    global $pdo;

    $sql = 'DELETE FROM outputs WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

/**
 * Подсчитывает количество неподтверждённых пользователей,
 * у которых загружены все четыре файла для верификации.
 *
 * @return int   — число таких пользователей или -1 при ошибке
 */
function countUnverifiedUsersWithFiles(): int {
    global $pdo;

    $sql = "
        SELECT COUNT(*) 
        FROM users AS u
        INNER JOIN user_verification AS uv 
            ON u.uid = uv.user_id
        WHERE u.verified = '0'
          AND uv.file1 IS NOT NULL AND uv.file1 != ''
          AND uv.file2 IS NOT NULL AND uv.file2 != ''
          AND uv.file3 IS NOT NULL AND uv.file3 != ''
          AND uv.file4 IS NOT NULL AND uv.file4 != ''
    ";

    // Используем prepare + execute для безопасности
    $stmt = $pdo->prepare($sql);
    if (!$stmt->execute()) {
        // при ошибке возвращаем -1
        return -1;
    }

    // fetchColumn() вернёт первую колонку (COUNT(*))
    return (int) $stmt->fetchColumn();
}

/**
 * Возвращает массив записей из user_verification для всех
 * неподтверждённых пользователей, у которых загружены 4 файла.
 *
 * @return array|null — массив ассоц. массивов или null при ошибке
 */
function getUnverifiedUsersWithFiles(): ?array {
    global $pdo;

    $sql = "
        SELECT uv.*
        FROM user_verification AS uv
        WHERE uv.user_id IN (
            SELECT u.uid
            FROM users AS u
            WHERE u.verified = '0'
        )
          AND uv.file1 IS NOT NULL AND uv.file1 != ''
          AND uv.file2 IS NOT NULL AND uv.file2 != ''
          AND uv.file3 IS NOT NULL AND uv.file3 != ''
          AND uv.file4 IS NOT NULL AND uv.file4 != ''
    ";

    $stmt = $pdo->prepare($sql);
    if (!$stmt->execute()) {
        return null;
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Возвращает все новые записи tokenization со статусом = 0
 *
 * @return array Массив ассоц. массивов записей или пустой массив
 */
function getNewTokenizationsByAllUsers(): array
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM tokenization WHERE status = 0");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Возвращает все записи tokenization, отсортированные по start_date DESC,
 * где ключом внешнего массива является id записи.
 *
 * @return array [ id => [ … запись … ], … ]
 */
function getAllTokenizations(): array
{
    global $pdo;

    $stmt = $pdo->query("SELECT * FROM tokenization ORDER BY date DESC");
    $rows = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[$row['id']] = $row;
    }

    return $rows;
}

/**
 * Возвращает все записи tokenization для конкретного пользователя,
 * где ключом внешнего массива является id записи.
 *
 * @param int $user_id
 * @return array [ id => [ … запись … ], … ]
 */
function getAllTokenizationsByUser(int $user_id): array
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM tokenization WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    $rows = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[$row['id']] = $row;
    }

    return $rows;
}

/**
 * Возвращает все записи tokenization для конкретного пользователя,
 * где ключом внешнего массива является id записи.
 *
 * @param int $user_id
 * @return array [ id => [ … запись … ], … ]
 */
function getAllTokenizationsByTokenId(int $id): array
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM tokenization WHERE id = :id");
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Устанавливает новый статус записи в таблице tokenization по её ID.
 *
 * @param int $id      — ID записи в tokenization
 * @param int $status  — новое значение поля status
 * @return bool        — true при успехе, false при ошибке
 */
function setStatusTokenRequestById(int $id, int $status): bool {
    global $pdo;

    $sql = "UPDATE tokenization
            SET status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        'status' => $status,
        'id'     => $id,
    ]);
}

/**
 * Создаёт новую заявку пользователя.
 *
 * @param int $userId  — ID авторизованного пользователя
 * @return bool        — true при успехе, false при ошибке
 */
function createTokenizationRequestByUser(int $userId): bool {
    global $pdo;

    $sql = "
        INSERT INTO tokenization
            (user_id)
        VALUES
            (:user_id)
    ";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['user_id' => $userId]);
}

/**
 * Генерирует случайную строку из $alphabet длиной $len
 */
function random_str(int $length, string $alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
{
    $chars = strlen($alphabet);
    $str   = '';
    for ($i = 0; $i < $length; $i++) {
        $str .= $alphabet[random_int(0, $chars - 1)];
    }
    return $str;
}

/**
 * Возвращает уникальный код активации длиной $len.
 * Повторяет генерацию, пока код не встретится в БД.
 */
function unique_activation(int $length): string
{
    global $pdo;

    do {
        $code = random_str($length);                     // генерируем
        $q = $pdo->prepare('SELECT 1 FROM users WHERE activation = ? LIMIT 1');
        $q->execute([$code]);
    } while ($q->fetchColumn());                      // если найден — пытаемся снова

    return $code;
}

/**
 * Вернуть строку следующего уровня из таблицы tariff_levels.
 * Использует фиксированный порядок уровней: L1..L7 → S1..S4 → V1..V3.
 * Если текущий уровень последний (V3) — вернёт плейсхолдеры.
 *
 * @param string $currentLevel Текущий уровень пользователя, напр. 'L3'
 * @return array{lvl:string,total_deposit_usd:string|float,income_usd:string|float,min_active_partners:int}
 */
function getNextLevel(string $currentLevel): array
{
    // Требуется глобальный PDO
    /** @var PDO $pdo */
    global $pdo;

    // Порядок уровней
    static $order = [
        'L1','L2','L3','L4','L5','L6','L7',
        'S1','S2','S3','S4',
        'V1','V2','V3',
    ];

    $cur = strtoupper(trim($currentLevel));
    $idx = array_search($cur, $order, true);

    // Если не распознали текущий уровень — целимся в самый первый
    if ($idx === false) {
        $nextLvl = $order[0];
    } elseif ($idx >= count($order) - 1) {
        // Уже максимальный уровень — отдаём плейсхолдер, чтобы верстка не падала
        return [
            'lvl'                  => '—',
            'total_deposit_usd'    => 0,
            'income_usd'           => 0,
            'min_active_partners'  => 0,
        ];
    } else {
        $nextLvl = $order[$idx + 1];
    }

    // Берём строку следующего уровня из БД
    $stmt = $pdo->prepare(
        'SELECT lvl, total_deposit_usd, income_usd, min_active_partners
         FROM tariff_levels
         WHERE lvl = :lvl
         LIMIT 1'
    );
    $stmt->execute([':lvl' => $nextLvl]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // На случай, если данных в таблице ещё нет — вернём безопасные значения
    if (!$row) {
        return [
            'lvl'                  => $nextLvl,
            'total_deposit_usd'    => 0,
            'income_usd'           => 0,
            'min_active_partners'  => 0,
        ];
    }

    return $row;
}
