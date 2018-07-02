<?php
@session_start();

class mySQLclass {
   public $sql_table = "";
   public $sql_result;
   public $sql_fields = array ();

   private $sql_host = "localhost";
   private $sql_database = "uwa";
   private $sql_user = "";
   private $sql_password = "";

   function __construct() 
   { if (getenv('SERVER_ADDR') != "127.0.0.1")
        { $this->sql_user = "opianfpe_wcs";
           $this->sql_password = "wcspass@123";
           $this->sql_database = "opianfpe_wcsportal";
        }
      mysql_connect ($this->sql_host, $this->sql_user, $this->sql_password);
      //@mysql_select_db ($this->sql_database);
      @mysql_select_db ($this->sql_database) or _scram ("select");
   }

   public function _sqlerrNo ()
   { return mysql_errno();
   }

   public function _sqlerror ()
   { $no = mysql_errno();
      $err = mysql_error();
      print <<<ERR
<DIV style="background: #A00000; color: #FFFFFF; font-weight: bold;">
ERROR ($no): $err
</DIV>
ERR;
   }

   public function _sqlerror2 (&$error)
   { $no = mysql_errno();
      $err = mysql_error();
      $error = <<<ERR
ERROR ($no): $err

ERR;
   }

   protected function _scram ($msg)
   { $no = mysql_errno();
      $err = mysql_error();
      print <<<ERR
<DIV style="background: #A00000; color: #FFFFFF; font-weight: bold;">
ERROR ($no): $err
</DIV>
ERR;
      die ($msg);
   }

   public function _sqlLookup ($query)
   {    $this->sql_result = mysql_query ($query);

   }

}

?> 
