<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
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
define( 'DB_NAME', 'wordpress_prueba_wpcargo' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'NJAQx<(7Gg>8^1inya^B_cnSO+s$KhGt)Imt)jbLcj0cC)&Dd;Ch`{,*k-q9WJzS' );
define( 'SECURE_AUTH_KEY',  'N#]{/#oz*Ik>z-+dn~;eeeA9V(eKdYu8!c#YsbrST({3xsslKmaQg^k}*op>m6:1' );
define( 'LOGGED_IN_KEY',    'h4)Iu/FVhjhgl(IvmhUQzT%^24EH dzY@|c`>y9$^_>|kSuQ-,1K-hm{3oZw@<@y' );
define( 'NONCE_KEY',        'O-Nm;(h?wie_,`Z;Ltdb5$WIL}%0}*;n]#=@z^ADVLrS`*.&{zKmCojiK>c$pP7%' );
define( 'AUTH_SALT',        'a5q{R~$*fg+F:S>I:W$+P#M|+C&,:9No.lQ)93&IPs7V&g*Yj%Biyrza/-IVoD4v' );
define( 'SECURE_AUTH_SALT', '2/o-bga3+b~bfq4FNK_C6@sOvN:z^63OyyPPl ivO61[vr nyFT{DL%6_oJr~o4(' );
define( 'LOGGED_IN_SALT',   'tt:lh^dkBpI!IMv^$midz~=ESZ~`^(#^zLKV1[{&i0@RbdUlaL.UN;S=AMTd1%Ik' );
define( 'NONCE_SALT',       '0:nYiQS2bl&fO:wJl1O$GUt!P-%s@pBYPC3&/.<Igafm$.xkYO|4@r7%:#F @tiH' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_cargo_';

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
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
