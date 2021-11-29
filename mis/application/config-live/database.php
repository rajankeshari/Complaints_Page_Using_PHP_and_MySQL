<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

//$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'misadmin';
$db['default']['password'] = 'iitm28jvffFvtKLwMXnn';
//$db['default']['password'] = 'ism28jvffFvtKLwMXnp';


$db['default']['hostname'] = 'localhost';
//$db['default']['username'] = 'misteam';
//$db['default']['password'] = 'CD8jYCXWnPxTfn39';
//$db['default']['username'] = 'root';
//$db['default']['password'] = 'vrbest#8.45';


$db['default']['database'] = 'mis_40_50';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/*
$db['feedback']['hostname'] = 'localhost';
$db['feedback']['username'] = 'root';
$db['feedback']['password'] = 'vrbest#8.50';
*/
$db['feedback']['hostname'] = 'localhost';
//$db['feedback']['username'] = 'root';
//$db['default']['username'] = 'misadmin';
//$db['default']['password'] = '28jvffFvtKLwMXnp';
$db['feedback']['username'] = 'misteam';
$db['feedback']['password'] = 'CD8jYCXWnPxTfn39';
//$db['feedback']['password'] = 'vrbest#8.45';
$db['feedback']['database'] = 'feedback';
$db['feedback']['dbdriver'] = 'mysql';
$db['feedback']['dbprefix'] = '';
$db['feedback']['pconnect'] = TRUE;
$db['feedback']['db_debug'] = FALSE;
$db['feedback']['cache_on'] = FALSE;
$db['feedback']['cachedir'] = '';
$db['feedback']['char_set'] = 'utf8';
$db['feedback']['dbcollat'] = 'utf8_general_ci';
$db['feedback']['swap_pre'] = '';
$db['feedback']['autoinit'] = TRUE;
$db['feedback']['stricton'] = FALSE;

//dipankar Sir Server Connectivity//
$db['tabulation']['hostname'] = '172.16.4.19';
$db['tabulation']['username'] = 'root';
$db['tabulation']['password'] = 'root789';
$db['tabulation']['database'] = 'tabulation';
$db['tabulation']['dbdriver'] = 'mysqli';
$db['tabulation']['dbprefix'] = '';
$db['tabulation']['pconnect'] = TRUE;
$db['tabulation']['db_debug'] = FALSE;
$db['tabulation']['cache_on'] = FALSE;
$db['tabulation']['cachedir'] = '';
$db['tabulation']['char_set'] = 'utf8';
$db['tabulation']['dbcollat'] = 'utf8_general_ci';
$db['tabulation']['swap_pre'] = '';
$db['tabulation']['autoinit'] = TRUE;
$db['tabulation']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
