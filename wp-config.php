<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'love-island-1' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'e{q#c4#V EW-,&iD&;44g;k8`K~gLN{l)kgt/*%[J:!wzB<TB4/*QiYlsL-8kVIu' );
define( 'SECURE_AUTH_KEY',  'ao!@.knGu{dvX ocA12b_DZ&G-{t@O@3FUie]Cz?0?Pnif7} c6s6Bz;~!)d/Ip3' );
define( 'LOGGED_IN_KEY',    '29 k#Y_%_?I+Lax)FC)/upTD@f|?CnJ-dtQq,m*R;62m3(q3Ytbd;,J/VHmc9DTl' );
define( 'NONCE_KEY',        '|n%^X@vx@q1f?u)|A^2~S!p$J_a&:yR<RgV^b_ZxyaG5Jm=O5X]Qq3i*uHWYDd[4' );
define( 'AUTH_SALT',        'T>s5*&+i%?n0n/W;*ejsC9~W=yK>%c|ts`{z{QJ^>=>y.vVLL?{n1^SK0Z0[,M06' );
define( 'SECURE_AUTH_SALT', 'd;[u{6;{OJ,/P<BAlxnIK=xV2H~g5PF2*+ND_p:JXuMIali#>8kJ`ngMFz[Fs7!g' );
define( 'LOGGED_IN_SALT',   '&cD^}.zn-AK)}2g#H3o&l)!;4LWQ=3OLuZXH(!,o!]6H(3MjXg8D6K#,qaaUP~q3' );
define( 'NONCE_SALT',       'm%W-Pm#v*B6bjHn93=rFGYU%9^q$At0n>~SzbO=@H?7WjR`)]rhrvQ~5CH;)?hPq' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
