<?php

/**
 * This class is intended to be a wrapper around the mysql functions.
 *
 * @author thk
 * @version 1.0.0
 *
 */

define ( 'RETURN_NUM_ARRAY',    1 );
define ( 'RETURN_ASSOC_ARRAY',  2 );
define ( 'RETURN_OBJECT',       3 );

class MySqlConn
{
    private $_server;               // Name of the db server
    private $_username;             // Name of user logging in to the db
    private $_password;             // Password for user identified in usernae
    private $_new_link;             // Reuse the current link or not
    private $_client_flags;         // Additional connection flags
    private $_debug_flag;           // Turn on/off debug print statements for all methods
    private $_link;                 // MySQL link identifier
    private $_query_string;         // String containing the MySQL query
    private $_result_set;           // Result of a query
    private $_num_returned_rows;    // Number of rows returned from a query
    private $_num_affected_rows;    // Number of rows affected from a query
    private $_row;                  // A row of data from the db
    private $_errmsg;               // Contains the current error message


    /**
     * The MySqlConnClass constructor.  See documentation for mysql_connect
     * for details.
     * @param string $s Name of the server to connect to
     * @param string $u Username to use for connecting
     * @param string $p Password to use for connecting
     * @param boolean $n Establish a new link or use an existing one
     * @param integer $c The client flags
     */
    public function MySqlConn ( $s='', $u='', $p='', $n=false, $c=0 )
    {
        $this->_server = $s;
        $this->_username = $u;
        $this->_password = $p;
        $this->_new_link = $n;
        $this->_client_flags = $c;
        $this->_debug_flag = false;
        $this->_errmsg = 'No error at this point... too early to tell... ;-)';
        $this->_num_affected_rows = -1;
        $this->_num_returned_rows = -1;
        $this->_row = null;
    }

    /**
     * Connects to a MySQL database.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return boolean
     * Returns true if successful, false otherwise.
     */
    public function connect_to_database ( $p_dbflag=false )
    {
        if ( !empty ( $this->_server ) &&    // The server, username and password
             !empty ( $this->_username ) &&  // are required to be set.  Check
             !empty ( $this->_password ) )   // to make sure they are set.
        {
            if ( $this->_debug_flag || $p_dbflag )
            {
                echo "<p>(dbg) Server: $this->_server<br />";
                echo "(dbg) Username: $this->_username<br />";
                echo "(dbg) Password: $this->_password<br />";
                echo "(dbg) New link: $this->_new_link<br />";
                echo "(dbg) Client flags: $this->_client_flags<br />";
            }

            $this->_link = mysql_connect ( $this->_server,
                                           $this->_username,
                                           $this->_password,
                                           $this->_new_link,
                                           $this->_client_flags );

            if ( $this->_link == false )
            {
                $emsg = 'connect_to_database: mysql_connect returned false. No db connection.<br />'
                      . 'The MySQL Error is: ' . mysql_error();
                $this->_set_error_message ( $emsg );
                return false;
            }
            else
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo "(dbg) connect_to_database: mysql_connect returned true. Connection successful.";
                return true;
            }
        }
        else
        {
            $emsg = 'connect_to_database: The server, username and password are required to be set.<br />'
                  . 'Currently, one or more of them are not set.  Fatal error; cannot continue.';
            $this->_set_error_message ( $emsg );
            return false;
        }
    }

    /**
     * Disconnects from a MySQL database.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return boolean
     * Returns true if successful, false otherwise.
     */
    public function disconnect_from_database ( $p_dbflag='' )
    {
        if ( $this->_debug_flag || $p_dbflag )
            echo "<p>(dbg) disconnect_from_database: Trying to disconnect from a database...</p>";

        if ( isset ( $this->_link ) &&
             !empty ( $this->_link ) &&
             !$this->_link == null )
        {
            $result = mysql_close ( $this->_link );
            if ( $result == true )
                return true;
            else
            {
                $emsg = 'disconnect_from_database: mysql_close() returned false.<br />'
                      . 'The MySQL Error is: ' . mysql_error();
                $this->_set_error_message ( $emsg );
                return false;
            }
        }
        else
        {
            $emsg = 'disconnect_from_database: Disconnecting from a database not currently connected.';
            $this->_set_error_message ( $emsg );
            return false;
        }
    }

    /**
     * Selects a database.
     * @param string $p_dbname
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return boolean
     * Returns true if successful, false otherwise.
     */
    public function select_database ( $p_dbname='', $p_dbflag=false )
    {
        if ( $this->_debug_flag || $p_dbflag )
            echo "<p>(dbg) select_database: Trying to select database named '$p_dbname'...</p>";

        $db_selected = mysql_select_db ( $p_dbname, $this->_link );
        if ( $db_selected )
            return true;
        else
        {
            $emsg = 'select_database: mysql_select_db() returned false.  Database not selected.<br />'
                  . 'The MySQL Error is: ' . mysql_error();
            $this->_set_error_message ( $emsg );

            return false;
        }
    }

    /**
     * Executes a query against a database.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return boolean
     * Returns true if successful, false otherwise.
     */
    public function execute_query ( $p_dbflag=false )
    {
        if ( $this->_debug_flag || $p_dbflag )
            echo "<p>(dbg) execute_query: Trying to execute query: '$this->_query_string'...</p>";

        $this->_result_set = mysql_query ( $this->_query_string, $this->_link );
        if ( $this->_result_set )
        {
            if ( $this->_debug_flag || $p_dbflag )
            {
                // These may product warnings depending on the type of query issued.
                // For debug mode, do them anyway
                $this->_num_returned_rows = mysql_num_rows ( $this->_result_set );
                $this->_num_affected_rows = mysql_affected_rows ( $this->_link );
                echo '<p>(dbg) execute_query: The query was successful.<br />';
                echo "(dbg) execute_query: rows returned = '$this->_num_returned_rows'.<br />";
                echo "(dbg) execute_query: rows affected = '$this->_num_affected_rows'.</p>";
            }
            else
            {
                // Disable the warning for non-debug mode.
                @$this->_num_returned_rows = mysql_num_rows ( $this->_result_set );
                @$this->_num_affected_rows = mysql_affected_rows ( $this->_link );
            }

            return true;
        }
        else
        {
            if ( $this->_debug_flag || $p_dbflag )
                echo '<p>(dbg) execute_query: mysql_query returned false.</p>';

            // If the query did not work, make sure the row counts reflect the
            // fact that the query did not work
            $this->_num_returned_rows = -1;
            $this->_num_affected_rows = -1;

            // Note that returning false is different from returning a returning
            // a 0 length result set.
            $emsg = 'execute_query: mysql_query() returned false.  Query not executed.<br />'
                  . 'The MySQL Error is: ' . mysql_error();
            if ( $this->_debug_flag || $p_dbflag )
                echo "<p>(dbg) execute_query: Setting error message to '$emsg'</p>";
            $this->_set_error_message ( $emsg );

            return false;
        }
    }

    /**
     * Returns the number of returned rows from a query.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return integer
     * Returns the number of returned rows if successful, 0 otherwise.
     */
    public function get_number_of_returned_rows ( $p_dbflag=false )
    {
        if ( $this->_debug_flag || $p_dbflag )
            echo "<p>(dbg) get_number_of_returned_rows: Rows returned from this query: $this->_num_returned_rows</p>";
        return ( $this->_num_returned_rows );
    }

    /**
     * Returns the number of affected rows from a query.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return integer
     * Returns the number of affected rows if successful, 0 otherwise.
     */
    public function get_number_of_affected_rows ( $p_dbflag=false )
    {
        if ( $this->_debug_flag || $p_dbflag )
            echo "<p>(dbg) get_number_of_affected_rows: Rows affected by this query: $this->_num_affected_rows</p>";
        return ( $this->_num_affected_rows );
    }

    /**
     * Returns the first row from the result set.
     * @param enum $p_how
     * An enumerated value representing the form the return value should take.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return integer
     * Returns the first row from the result set if successful, false otherwise.
     */
    public function get_first_result ( $p_how=RETURN_ASSOC_ARRAY, $p_dbflag=false )
    {
        if ( $this->_num_returned_rows == -1 || $this->_num_affected_rows == -1 )
        {
            if ( $this->_debug_flag || $p_dbflag )
                echo '<p>(dbg) get_first_result: The query did not work as either _num_returned_rows or _num_affected_rows are -1. Returning false.</p>';
            return false;
        }
        else
        {
            if ( $this->_debug_flag || $p_dbflag )
            {
                // The seek produces a warning if there are no records in the
                // result set.  So, only show the warning if debugging
                echo '<p>(dbg) get_first_result: Seeking to the first record...</p>';
                $result = mysql_data_seek ( $this->_result_set, 0 );
            }
            else
            {
                @$result = mysql_data_seek ( $this->_result_set, 0 );
            }
            if ( $result )
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_first_result: mysql_data_seek() was successful.  Continuing...</p>';
            }
            else
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_first_result: mysql_data_seek() returned false.  Returning false...</p>';
                return false;
            }

            $result = $this->_get_result ( $p_how, $p_dbflag );
            if ( $result )
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_first_result: _get_result() was successful.  Returning the row...</p>';
                return $this->_row;
            }
            else
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_first_result: _get_result() returned false.  Returning false...</p>';
                return false;
            }
        }
    }

    /**
     * Returns the next row from the result set.
     * @param enum $p_how
     * An enumerated value representing the form the return value should take.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return integer
     * Returns the next row from the result set if successful, false otherwise.
     */
    public function get_next_result ( $p_how=RETURN_ASSOC_ARRAY, $p_dbflag=false )
    {
        if ( $this->_num_returned_rows == -1 || $this->_num_affected_rows == -1 )
        {
            if ( $this->_debug_flag || $p_dbflag )
                echo '<p>(dbg) get_next_result: The query did not work as either _num_returned_rows or _num_affected_rows are -1. Returning false.</p>';
            return false;
        }
        else
        {
            $result = $this->_get_result ( $p_how, $p_dbflag );
            if ( $result )
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_next_result: _get_result() was successful.  Returning the row...</p>';
                return $this->_row;
            }
            else
            {
                if ( $this->_debug_flag || $p_dbflag )
                    echo '<p>(dbg) get_next_result: _get_result() returned false.  Returning false...</p>';
                return false;
            }
        }
    }

    /**
     * Returns the complete result set.
     * @param enum $p_how
     * An enumerated value representing the form the return value should take.
     * @param boolean $p_dbflag
     * Flag to turn debugging on and off.  On=true, Off=false.
     * @return array
     * Returns an array if successful, false otherwise.
     */
    public function get_result ( $p_how=RETURN_ASSOC_ARRAY, $p_dbflag=false )
    {
        if ( $this->_num_returned_rows == -1 || $this->_num_affected_rows == -1 )
        {
            if ( $this->_debug_flag || $p_dbflag )
                echo '<p>(dbg) get_next_result: The query did not work as either _num_returned_rows or _num_affected_rows are -1. Returning false.</p>';
            return false;
        }
        else
        {
            $num_rows = $this->_num_returned_rows;
            $myRow = $this->get_first_result ( $p_how );
            for ( $i=1 ; $i<=$num_rows ; $i++ )
            {
                $resultArray[$i] = $myRow;
                $myRow = $this->get_next_result ( $p_how );
            }
        }
        return $resultArray;
    }

    /**
     * Returns the error message.
     * @return string
     * Returns the last error message.
     */
    public function get_error_message ()
    {
        return $this->_errmsg;
    }

    /**************************************************************************
     *
     * Helper functions
     *
     *************************************************************************/

    private function _get_result ( $p_how=RETURN_ASSOC_ARRAY, $p_dbflag=false )
    {
        $result = false;

        switch ( $p_how )
        {
            case RETURN_NUM_ARRAY:
                $result = mysql_fetch_array ( $this->_result_set, MYSQL_NUM );
                if ( $result )
                    $this->_row = $result;
                else
                    $this->_row = null;
                break;

            case RETURN_ASSOC_ARRAY:
                $result = mysql_fetch_array ( $this->_result_set, MYSQL_ASSOC );
                if ( $result )
                    $this->_row = $result;
                else
                    $this->_row = null;
                break;

            case RETURN_OBJECT:
                $result = mysql_fetch_object ( $this->_result_set );
                if ( $result )
                    $this->_row = $result;
                else
                    $this->_row = null;
                break;

            default:
                // If we get here, something is terribly wrong!
                $this->_row = null;
                break;
        }

        return $result;
    }

    private function _set_error_message ( $s )
    {
        // This is "internal" only; user cannot set error messages
        $this->_errmsg = $s;
    }

    /*************************************************************************
     *
     * Getters and setters
     *
     ************************************************************************/

    public function get_server()
    {
        return $this->_server;
    }

    public function get_username()
    {
        return $this->_username;
    }

    public function get_password()
    {
        return $this->_password;
    }

    public function get_new_link()
    {
        return $this->_new_link;
    }

    public function get_client_flags()
    {
        return $this->_client_flags;
    }

    public function get_debug_flag()
    {
        return $this->_debug_flag;
    }

    public function get_query_string()
    {
        return $this->_query_string;
    }

    public function set_server ( $s )
    {
        $this->_server = $s;
    }

    public function set_username ( $u )
    {
        $this->_username = $u;
    }

    public function set_password ( $p )
    {
        $this->_password = $p;
    }

    public function set_new_link ( $n )
    {
        $this->_new_link = $n;
    }

    public function set_client_flags ( $c )
    {
        $this->_client_flags = $c;
    }

    public function set_debug_flag ( $d )
    {
        $this->_debug_flag = $d;
    }

    public function set_query_string ( $qs )
    {
        $this->_query_string = $qs;
    }

}

?>
