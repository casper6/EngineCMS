<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "sql_layer.php")) { Header("Location: ../index.php"); die(); }

# $dbtype = "MySQL";
# $dbtype = "postgres";
# $dbtype = "postgres_local"; // Когда postmaster запускается без опции "-i".

# sql_connect($host, $user, $password, $db) — возвращает ID соединения.

class ResultSet {
	var $result;
	var $total_rows;
	var $fetched_rows;
	function set_result( $res )        { $this->result = $res; }
	function get_result()              { return $this->result; }
	function set_total_rows( $rows )   { $this->total_rows = $rows; }
	function get_total_rows()          { return $this->total_rows; }
	function set_fetched_rows( $rows ) { $this->fetched_rows = $rows; }
	function get_fetched_rows()        { return $this->fetched_rows; }
	function increment_fetched_rows()  { $this->fetched_rows = $this->fetched_rows + 1; }
}

function sql_connect($host, $user, $password, $db) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $dbi=@mysql_connect($host, $user, $password);
	mysql_select_db($db);
        return $dbi;
    break;;

    case "postgres":
         $dbi=@pg_connect("host=$host user=$user password=$password port=5432 dbname=$db");
         return $dbi;
    break;;

    case "postgres_local":
         $dbi=@pg_connect("user=$user password=$password dbname=$db");
         return $dbi;
    break;;

    default:
    break;;
    }
}

function sql_logout($id) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $dbi=@mysql_close($id);
        return $dbi;
    break;;

    case "postgres":
    case "postgres_local":
         $dbi=@pg_close($id);
         return $dbi;
    break;;

    default:
    break;;
    }
}


/*
 * sql_query($query, $id)
 * executes an SQL statement, returns a result identifier
 */

function sql_query($query, $id) {
global $siteurl, $dbtype, $sql_debug;
$sql_debug = 0;
if($sql_debug) echo "SQL query: ".str_replace(",",", ",$query)."<BR>";
switch ($dbtype) {
    case "MySQL":
        $res=@mysql_query($query, $id);
        return $res;
    break;;

    case "postgres":
    case "postgres_local":
	$res=pg_exec($id,$query);
	$result_set = new ResultSet;
	$result_set->set_result( $res );
	$result_set->set_total_rows( sql_num_rows( $result_set ) );
	$result_set->set_fetched_rows( 0 );
        return $result_set;
    break;;

    default:
    break;;
    }
}

/*
 * sql_num_rows($res)
 * given a result identifier, returns the number of affected rows
 */

function sql_num_rows($res) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $rows=mysql_num_rows($res);
        return $rows;
    break;;

    case "postgres":
    case "postgres_local":
        $rows=pg_numrows( $res->get_result() );
        return $rows;
    break;;

    default:
    break;;
    }
}

/*
 * sql_fetch_row(&$res,$row)
 * given a result identifier, returns an array with the resulting row
 * Needs also a row number for compatibility with postgres
 */

function sql_fetch_row(&$res, $nr=0) {
global $siteurl, $dbtype;
switch ($dbtype) {

    case "MySQL":
        $row = mysql_fetch_row($res);
        return $row;
    break;;

    case "postgres":
    case "postgres_local":
	if ( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_row($res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		return $row;
	} else {
		return false;
	}
    break;;

    default:
    break;;
    }
}

/*
 * sql_fetch_array($res,$row)
 * given a result identifier, returns an associative array
 * with the resulting row using field names as keys.
 * Needs also a row number for compatibility with postgres.
 */

function sql_fetch_array(&$res, $nr=0) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $row = array();
        $row = mysql_fetch_array($res);
        return $row;
    break;;

    case "postgres":
    case "postgres_local":
	if( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = array();
		$row = pg_fetch_array($res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		return $row;
	} else {
		return false;
	}
    break;;
    }
}

function sql_fetch_object(&$res, $nr=0) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $row = mysql_fetch_object($res);
	if($row) return $row;
	else return false;
    break;;

    case "postgres":
    case "postgres_local":
	if( $res->get_total_rows() > $res->get_fetched_rows() ) {
		$row = pg_fetch_object( $res->get_result(), $res->get_fetched_rows() );
		$res->increment_fetched_rows();
		if($row) return $row;
		else return false;
	} else {
		return false;
	}
    break;;
    }
}
/*** Function Free Result for function free the memory ***/
function sql_free_result($res) {
global $siteurl, $dbtype;
switch ($dbtype) {
    case "MySQL":
        $row = mysql_free_result($res);
        return $row;
    break;;

	case "postgres":
    case "postgres_local":
        $rows=pg_FreeResult( $res->get_result() );
        return $rows;
    break;;
	}
}
?>