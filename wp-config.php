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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'rd-vars-land' );

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
define( 'AUTH_KEY',         'd/X*y;:ym5Zm8H?D?@7axm<M}aK>jFa9Q-x<:p;~38Dr4fICCkSCa~Gn5tn*8?nM' );
define( 'SECURE_AUTH_KEY',  '=.neXRFSddFH=DljM8C{@_^8h9[PVpvpjw5V?YsP:b3L<4XY=UORS#iau5<!Y+_G' );
define( 'LOGGED_IN_KEY',    '5|x[#jEqDb /E1u:<[]8,&L//T[49?mdXann1r.E{@7nH3@&a^_Rg`|a^&wPh*!q' );
define( 'NONCE_KEY',        'f^uN{2{OEf)wI]d:0~iR+ZAVSaimAzl:7@d]P,;3e6XikK;6x-V^CDZn%i,%y4$n' );
define( 'AUTH_SALT',        'tqn<!z.cADS,-m1o-B1#y0=i,_RJCozD4f6[jx$fQ)v^bvltlU*rA/~F#,YY4PFz' );
define( 'SECURE_AUTH_SALT', ' xFv-EsQ!O8cD)%d,F20mCU9ScD0ap>12Qyk4^x?RS[;7{Z4IN|8,X?/?~u`O=f#' );
define( 'LOGGED_IN_SALT',   'wx~YKQTE3N`?oKasNOAj^D@;iz^&vci-PCeE[]n>@T^>+^Yu<,u|%]LK4X%nZJ5/' );
define( 'NONCE_SALT',       'O@=epVxA?n2D~I2#l=r~wc}kjP-cs2NU^?+Dl7[*}uIE*ZB+xnJ6f7GvDi@/[toq' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'rd_';

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
