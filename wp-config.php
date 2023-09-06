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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'pj2023' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



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
define( 'AUTH_KEY',         'SQWDVnzjylSDduAnQcxWqvCwwm9vREDKyh21J8FvotNB1vgQVZC3PP1XOGn1vsZT' );
define( 'SECURE_AUTH_KEY',  '6zDj7Mxfqi6x6OmXEInkKzlCd3JD8iq87RPFm4HyOMgqbtIKzZhTb2jLytebst9b' );
define( 'LOGGED_IN_KEY',    'RGy1I9Gex5M7a6RO9ytw1oOJAMnD28SlSEN8sP4Ub6rgwITX66Q7L9YCRboiNmSG' );
define( 'NONCE_KEY',        'n0J99i6xezlT6BvF280L7dAZCrN2jjYLlz9qp6QiwxrwwR5d7pev5m9EcsB5Dsw0' );
define( 'AUTH_SALT',        'PYe8KbOpPM8utGFg2SIhUiaxjPEeG7KKYv4IytfrShpDHsuef2kYxQVX3QAh88on' );
define( 'SECURE_AUTH_SALT', 'rVrsmiKlGgR5HR1ZgG3DrKHmQTomkURlGR1zGzCceNYCXThQ5SFNz0938pEr2obJ' );
define( 'LOGGED_IN_SALT',   'bd16zZ49Kma31Ugdi7AszHAC3vhMMwEP3aLvnpLqBKCSSnAWE0AxfU8xbHcLFzxB' );
define( 'NONCE_SALT',       '5Dh4MWAX6KrYb4IyByT9q1q05O48v9LXsTx15WSgZuSZTHgDVELDttxOs0Q3KlOB' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
