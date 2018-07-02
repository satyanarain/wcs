<?php
@session_start();

class ip2Countryclass extends mySQLclass
{ public $cc2 = "";
   public $flag = "";
   public $country = "";
   private $dbList = "ip2c";

   public function _lookup ($dip) 
   { if (getenv('SERVER_ADDR') == "127.0.0.1") $this->dbList = "ip2country";
      $ip = ip2long ($dip);
      parent::_sqlLookup ("SELECT * FROM $this->dbList WHERE IP_FROM <= $ip and $ip <= IP_TO LIMIT 0,1");
      if ($this->sql_result === false) $this->_sqlerror ();

      if ($row = mysql_fetch_assoc ($this->sql_result))
         { $this->cc2 = $row['COUNTRY_CODE2'];
            $this->country = $row['COUNTRY_NAME'];
            $this->flag = "ip2c/flags/png/" . strtolower ($this->cc2) . ".png";
          } else { $flag = "ip2c/flags/png/starflag.png";
                        $this->country = "Not resolved";
                    }
   }
}

?> 
