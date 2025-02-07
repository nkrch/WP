<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'iorder' );

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
define( 'AUTH_KEY',         '!q.WH8qP]1qo[CL.JIK)+}:PqQPn7?@^0|9XIW 3k`!z~eG}J1~JL:4uK@BpFG5[' );
define( 'SECURE_AUTH_KEY',  'M!N58 hZ=d*Q8y~/whT3-u[A_(:qsS,+c7L[M([uNI;h6Ae13?`!owBnmADI1P@2' );
define( 'LOGGED_IN_KEY',    '<:q(E&Nue*#FlHOcNMxLe@|.u=P#>lcYzF?GeA?dy=]`onR!XVmN~mK`meUudA}C' );
define( 'NONCE_KEY',        ']I/kP1ORp.e~-)gtXR/^0]p uE97(MKTs;;0:t~g{?@$W0&XksMEg#bmSr.LXZrM' );
define( 'AUTH_SALT',        'DQ1e~!.-6u@|NU(CvSjh+LcF0=$XC)48(ta5iM6]abSL3{/XFD@gfytlJl.oARpV' );
define( 'SECURE_AUTH_SALT', 'giI$HHmE#A4O$o:g~y9rbX}sSqbPG/+Y%o%/hR:A!K9F6Gx*yvPD-I2@Fi~X6JF=' );
define( 'LOGGED_IN_SALT',   'z`JNVw#ykOub|@fR->I9I=4#!zu<s#mBf$CRmoB.?jk0*U{$ht-=9A-PK GRYkrp' );
define( 'NONCE_SALT',       '%BOg%9[<jX!}qN93hd4`_? gNS9eI:3aOjWuN,|JRiU8gg}`[}nJxOkAgqoP#jD&' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
