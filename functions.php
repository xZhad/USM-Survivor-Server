<?php

class Functions
{
	
	var $dbh = null;
    var $conexion = null;
	
	function Connect()
	{
		$this->dbh = @mysql_connect("localhost", "user", "pass") or die(mysql_error());
        $this->conexion = mysql_select_db("db", $this->dbh) or die(mysql_error($this->dbh));
		mysql_query ("SET NAMES 'utf8'");
	}
	
	function Disconnect()
	{
		mysql_close($this->dbh);
	}
	
	function Query($query)
    {
        $result = mysql_query($query, $this->dbh) or die ("Error al intentar enviar la información hacia el servidor ".mysql_error());
        return $result;
    }
	
	function Fetch_Assoc($resp)
    {
        $sres = array();
        while($result = mysql_fetch_assoc($resp))
            $sres[] = $result;

        return $sres;
    }

    function Num_Rows($resp)
    {
        return mysql_num_rows($resp);
    }

    function Fetch_Array($resp)
    {
        return mysql_fetch_array($resp,MYSQL_BOTH);
    }

    function Fetch_Array_Masive($resp)
    {
        $sres = array();
        while($result = mysql_fetch_array($resp,MYSQL_BOTH))
            $sres[] = $result;
        return $sres;
    }
	
}
?>