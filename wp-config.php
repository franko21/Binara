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
define( 'DB_NAME', 'diseno' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         '*gj|$7J?u1<V7xymAPQ|z7US2?>CH`MM19ZnZem}K Lv_@BMhH*y^wEC<(/[Tw3[' );
define( 'SECURE_AUTH_KEY',  '?#zo bo+!zguag}0 -(O]/W![Or[7d,pN2@z=<qc&gzNqL(K+t46?@src::,Y5<t' );
define( 'LOGGED_IN_KEY',    '>q_TUmN/R?jJsudjo/l?=7Jd,h7HB#`;oK{G~oxDi5_+u?VDw~R  v~2_m:VP gL' );
define( 'NONCE_KEY',        '#^sJb8!q@6x6FuLRJ*Zp0+I1zzYQ2=!vBtjJ_L<^]-utB`$-z{kj;_AM~iAkVd4b' );
define( 'AUTH_SALT',        'U}2U2|[7^eg^8vn-NFEOn0zlT$yhL(#&r<*L9WP $N4ef)&-$$XWYk3N6TP:ojn{' );
define( 'SECURE_AUTH_SALT', '<j_[_P.r/BX4kO{:LQxY^%h@NPo|)GSXw&@*N,^iju{k<X|F*-Dy0B(GA/4fspok' );
define( 'LOGGED_IN_SALT',   'C$tPIZ&~Ygx+!SOS~+^!$id9gaA51qJ4eq|V(|29L{z~iHTS{R$;09Q[pR_pF6 ;' );
define( 'NONCE_SALT',       'a=3RT~6r=..>R e}?@o6-N:OB`>DX1?>?^0};Q@mY@6GZ(:ee,pGPwS3[9SgM+i~' );

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
