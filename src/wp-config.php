<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'possewiki_dev');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('API_KEY', 'BebAcaiWr');

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '[y{f3b+e|>+7H8B,6{[B|f*&:qxZCF3(XoU*H24Sn[!q(gy#iESE<#+E6{<#Qx3j');
define('SECURE_AUTH_KEY',  ':$4c4>r[++-n;MS)v|+kRnBh?7Hp GeO8lJS%+;SuO?-Nc:X;+Z%}%XPh7<d=^I{');
define('LOGGED_IN_KEY',    'x#cXmjReGhfbY%cf+^U6YK4rpBF` F9d&&ZcKGx[Cyux$.|&tr+)!59<TF+kzEK$');
define('NONCE_KEY',        'x^lZH+>n8DzF+xphOX;K}}`]lHHyQ,h4u0]/?%QscTfI}gijB$olO$?/r5op0mwq');
define('AUTH_SALT',        'fJ(ji_F/-t$d}$b#aYvvC=m|AOtVzP&i,$7_P(gGwDxXIx1eR^dtR+Lq4k-:RT.z');
define('SECURE_AUTH_SALT', ':m@3^RPD|SLXYsK&6dA:T:]hxyT ]qN:L:CKgN$EOsja5Kh}}:.D)d_`O|!y*U1(');
define('LOGGED_IN_SALT',   '3+=ucK.*xCak[7DbY+Gb-Tu9*:*t(:vzWgX~mUZoJ^qQ9L/c/<hZU Zty)k=^[Gw');
define('NONCE_SALT',       '|-_|V|p(2Jl[cGgG@PXhXr9?csAUVGE7RDbcE+ 6;RGVI+rNpyLz%GHQYu-=2{:)');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
