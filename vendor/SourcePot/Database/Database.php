<?php

namespace SourcePot\Database;

class Database extends \PDO
{

   // Maintain a list of open database connections
   private static array $connections = [];

   /**
    * Allow for creating many different connections to the database
    * and keeping them open for the duration of script execution
    */
   public static function pool(
      string $username, string $password, string $dbname = '',
      string $host = 'localhost', int $port = 3306
   ): self
   {
      $hash = md5($username.$password.$dbname.$host.$port);
      if( !isset(self::$connections[$hash]) )
      {
         self::$connections[ $hash ] = new self( $username,$password,$dbname,$host,$port );
      }

      return self::$connections[ $hash ];
   }

   public function __construct( 
      private string $username, 
      private string $password, 
      private string $dbname = '', 
      private string $host = 'localhost', 
      private int $port = 3306
   ) {

      // Create database connection string
      $dsn = "mysql:host={$this->host};port={$this->port}";
      if( $this->dbname ) $dsn .= ";dbname={$this->dbname}";

      // Initialise PDO and connect to database
      parent::__construct( $dsn, $this->username, $this->password );

      // Set a handy default: automatically fetch stdclass objects
      $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
   }

}
