<?php
/* (C) 2001 phpBB Group */
if (stristr($_SERVER['PHP_SELF'], "db.php")) {
	Header("Location: index.php");
	die();
}
//include("db/mysql.php");
if(!defined("SQL_LAYER")) {
define("SQL_LAYER","mysql");
class sql_db {
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $num_q = "";
	// Constructor
	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)	{
		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;
		if($this->persistency) $this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
		else $this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
		if($this->db_connect_id) {
			if($database != "") {
				$this->dbname = $database;
				$dbselect = @mysql_select_db($this->dbname);
				if(!$dbselect) {
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}
			return $this->db_connect_id;
		} else return false;
	}
	// Other base methods
	function sql_close() {
		if($this->db_connect_id) {
			if($this->query_result) @mysql_free_result($this->query_result);
			$result = @mysql_close($this->db_connect_id);
			return $result;
		} else return false;
	}
	// Base query method
	function sql_query($query = "", $transaction = FALSE) {
		// Remove any pre-existing queries
		unset($this->query_result);
		if($query != "") {
			$this->query_result = @mysql_query($query, $this->db_connect_id);
			$this->num_queries++; // Подсчет кол-ва обращений к БД
			$end_time = microtime();
			$end_array = explode(" ",$end_time);
			$end_time = $end_array[1] + $end_array[0];
			$this->num_q .= $end_time." --- ".$query."\n"; // Список обращений ##################
		}
		if($this->query_result) {
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		} else return ( $transaction == END_TRANSACTION ) ? true : false;
	}
	// Other query methods
	function sql_numrows($query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_num_rows($query_id);
			return $result;
		} else return false;
	}
	function sql_affectedrows() {
		if($this->db_connect_id) {
			$result = @mysql_affected_rows($this->db_connect_id);
			return $result;
		} else return false;
	}
	function sql_numfields($query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_num_fields($query_id);
			return $result;
		} else return false;
	}
	function sql_fieldname($offset, $query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		} else return false;
	}
	function sql_fieldtype($offset, $query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		} else return false;
	}
	/*
	function sql_fetchrow($query_id = 0) {
		if( !$query_id ) {
			$query_id = $this->result;
		}
		if( $query_id ) {
			empty($row);
			$row = @mssql_fetch_array($query_id);
			while( list($key, $value) = @each($row) ) {
				$row[$key] = ($value === ' ') ? '' : stripslashes($value);
			}
			@reset($row);
			return $row;
		} else {
			return false;
		}
	}
	*/

	function sql_fetchrow($query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id and !is_integer($query_id)) {
return mysql_fetch_array($query_id);
			//$this->row[$query_id] = @mysql_fetch_array($query_id);
			//return $this->row[$query_id];
		} else return false;
	}

	function sql_fetchrowset($query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id)) {
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		} else return false;
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			if($rownum > -1) $result = @mysql_result($query_id, $rownum, $field);
			else {
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id])) {
					if($this->sql_fetchrow()) $result = $this->row[$query_id][$field];
				} else {
					if($this->rowset[$query_id]) $result = $this->rowset[$query_id][0][$field];
					else if($this->row[$query_id]) $result = $this->row[$query_id][$field];
				}
			}
			return $result;
		} else return false;
	}
	function sql_rowseek($rownum, $query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if($query_id) {
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		} else return false;
	}
	function sql_nextid() {
		if($this->db_connect_id) {
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		} else return false;
	}
	function sql_freeresult($query_id = 0) {
		if(!$query_id) $query_id = $this->query_result;
		if ( $query_id ) {
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);
			@mysql_free_result($query_id);
			return true;
		} else return false;
	}
	function sql_error($query_id = 0) {
		$result["message"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);
		return $result;
	}
} // class sql_db

} // if ... define
///////////////////////////
$db = new sql_db($dbhost, $dbuname, $dbpass, $dbname, false);
mysql_query ("set character_set_client='utf8'");
mysql_query ("set character_set_results='utf8'");
mysql_query ("set collation_connection='utf8_general_ci'");
if(!$db->db_connect_id) {
	die("<br><br><center><br><br><b>В данный момент проводится техническое обслуживание сервера.<br><br>Приносим свои извинения и просим Вас зайти немного позже.</center></b>");
}
?>