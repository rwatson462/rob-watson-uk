<?php
/**
 * Every request for a file that doesn't physically exists
 * is directed to this script.  We need to figure out the
 * file that was requested, then check the `pages` table
 * to see if an entry exists there. 
 * If an entry exists:
 * - Load the referenced template and pass in the page's
 *   specific data
 * Otherwise:
 * - Show the 404 template
 */


// load config file from outside the public directory
include dirname(__DIR__) . '/config.php';

// include database class and fetch an instance
include dirname(__DIR__) . '/vendor/SourcePot/Database/Database.php';
use SourcePot\Database\Database;

$db = Database::pool(
   host:     DB_HOST,
   username: DB_USER,
   password: DB_PASS,
   dbname:   DB_NAME
);


// sanitise the url to check in the pages table using

// step 1: remove any query string
$url = explode('?', $_SERVER['REQUEST_URI'])[0];
// step 2: remove leading and trailing slashes, then prepend a single slash
$url = '/' . trim( $url, '/' );


// check if page exists in pages table
$query = 'SELECT template, extra_json FROM pages WHERE url = :url AND active = 1';
$stmt = $db->prepare($query);
$stmt->execute([ 'url' => $url ]);

if( $stmt->rowCount() === 0 )
{
   // show 404 page if the page doesn't exist
   readfile(__DIR__.'/404.html');
   exit;
}

// get page record information, decode extra_json and assign to global variable
// so we can use it in the templates
$pageData = $stmt->fetch();

// wrap the inclusion in a function to preserve variable sope and have $data
// automatically available in the template
function includeTemplate( string $templateFile, object $data )
{
   include $templateFile;
}

includeTemplate( 
   TEMPLATE_DIR . $pageData->template, 
   json_decode($pageData->extra_json)
);
