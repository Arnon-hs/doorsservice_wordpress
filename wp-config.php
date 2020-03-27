<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'db_doors' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'root' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '_-o>Qi.lgX.kN;xfv~<|mbM]+`6zKe#-S&.xADa%0c+u3h}!-cqS7FI~5FN}kNuV' );
define( 'SECURE_AUTH_KEY',  '}YE7@ vNDPo40)d~(7B}gW@hi [*_z0-oJ|-4JM}Gp=9r6|69rYPcAUES#ySfxgk' );
define( 'LOGGED_IN_KEY',    'GQ2u@dgePfqM:B/MYyVH& 2$ZLQJGw~g<ggBQ&UWe+b!Co1ql^=<~Vk)0i3yZjT=' );
define( 'NONCE_KEY',        'Sbg*+8.5U,%a~vt4a2h&_C3=]J,4FA94=AjJ#&d/p{R+ce-[bO$yG]g%s@l)oc96' );
define( 'AUTH_SALT',        '7bp{ *4]>G`l9I<kq2VRlrkiF){MMn#~AJ~g8IQe0U^4L/[xkMl?j*Rf,G3|;P/B' );
define( 'SECURE_AUTH_SALT', '3Pva(V%M{Z;$1>]81x#+7}HCzlf`Tu9[r/+:qKo|riRsAIt.T.Iu09|h)~%F{K$x' );
define( 'LOGGED_IN_SALT',   'Q>7jWD:xq0`%74YI!qAw+aL{xMve.}d}2#lm4u@|At$TC-<z9EPJJt&dWLF& w?T' );
define( 'NONCE_SALT',       'Q!E?j<CGlSxWDblx8VU(d4~p*&.}eLs?O{hOQ$X_:YM9O!.S:2:Lm5Q F-Nsxhj2' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
