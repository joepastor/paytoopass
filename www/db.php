<?php
class db {
    private $usuario = "root";
    private $password="";
    private $host="127.0.0.1";

    function __construct(){
        $db = mysql_connect($this->host, $this->usuario, $this->password);
        $db_selected = mysql_select_db("sarita", $db);
    }

    function sqlQuery($sql){
		global $db;
        $rs = mysql_query($sql);
        return $rs;
    }
}
?>
