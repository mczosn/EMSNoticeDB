<?php   // <file: Sanitizer.class.php>

/**
 * This file contains the Sanitizer class defintions.  Sanitizer is a data
 * sanitization system.
 *
 * @author Timothy H. Knautz
 * @version 1.0.0
 * @package Sanitizer
 */

/**
 * Error numbers
 */
define ( 'S_ERRNUM_BASE', 1000 );
define ( 'S_ERRNUM_MISSING_PARAMETER', S_ERRNUM_BASE );
define ( 'S_ERRNUM_INVALID_TYPE', S_ERRNUM_BASE + 1 );
define ( 'S_ERRNUM_UNKNOWN_PHP_TYPE', S_ERRNUM_BASE + 2 );

/**
 * Error messages
 */
define ( 'S_ERRMSG_MISSING_PARAMETER', 'Missing a required parameter.' );
define ( 'S_ERRMSG_INVALID_TYPE', 'Invalid parameter type.' );
define ( 'S_ERRMSG_UNKNOWN_PHP_TYPE', 'Unknown PHP type.' );

/**
 * The Sanitizer class implements the Sanitizer data sanitization system.
 *
 * Sanitizer is a data sanitization system.  The purpose is to ensure that data
 * received is what is expected.  The class is implemented as a "static" class;
 * all of the methods are static.
 *
 * Usage example: $result = Sanitizer::sanitize_one ( $i, 'number_integer' );
 */
class Sanitizer
{
    /**
     * Sanitize only one variable.
     *
     * Returns the variable sanitized according to the desired type.  Strings
     * are always trimmed.
     *
     * @param mixed $pVariable The variable itself
     * @param string $pType A string containing the desired variable type.  The
     * type string can contain more than 1 type by seperating the types with a
     * comma.  E.g., 'string_nohtml, string_lower'.  The types are applied in
     * the order in which they are given in the type string.
     * @param boolean $pTrimFlag Flag indicating whether strings should be
     * trimmed.
     * @return type The sanitized variable
     */
    public static function sanitize_one ( $pVariable, $pType, $pTrimFlag=true )
    {
        try
        {
            Sanitizer::_test_required_parameter ( $pVariable, 'mixed' );
            Sanitizer::_test_required_parameter ( $pType, 'string' );
            Sanitizer::_test_optional_parameter ( $pTrimFlag, 'boolean' );
        }
        catch ( SanitizerException $e )
        {
            throw $e;
        }

        $var = $pVariable;

        // Trim strings if the asked to do so.
        if ( $pTrimFlag )
        {
            if ( is_string ( $var ) )
                $var = trim ( $var );
        }

        // Process the type string
        $applyArray = explode ( ',', $pType );

        // Apply the types
        foreach ( $applyArray as $type )
        {
            switch ( $type )
            {
                case 'number_integer':      // integer
                    // Number: integer
                    $var = (int) $var;
                    break;

                case 'number_float':        // float
                    $var = (float) $var;
                    break;

                case 'string_plain':        // convert html to entities, keep quotes
                    $var = htmlentities ( $var, ENT_NOQUOTES );
                    break;

                case 'string_nohtml':       // convert html to entities, convert quotes to entity
                    $var = htmlentities ( $var, ENT_QUOTES );
                    break;

                case 'string_ucwords':      // upper case words
                    $var = ucwords ( strtolower ( $var ) );
                    break;

                case 'string_ucfirst':      // upper case first word
                    $var = ucfirst ( strtolower ( $var ) );
                    break;

                case 'string_lower':        // lower case words
                    $var = strtolower ( $var );
                    break;

                case 'string_upper':        // lower case words
                    $var = strtoupper ( $var );
                    break;

                case 'string_urlencode':    // url encoded
                    $var = urlencode ( $var );
                    break;

                case 'string_dbsafe':    // database safe
                    // Do not want the string escaped twice, so check 
                    // get_magic_quotes().  Automatic escaping is highly deprecated,
                    // but we'll anyway to protect ourselves from stupid customers
                    // and hackers.
                    if ( !get_magic_quotes_gpc() )
                    {
                        $var = Sanitizer::_mysql_real_escape_string_mimic ( $var );
                    }
                    break;
                    
                case 'string_email': // True/False for an email address
                    $address = stripslashes ( $var );
                    $regex = "/^[_+a-z0-9-]+(\.[_+a-z0-9-]+)*" .
                             "@[a-z0-9-]+(\.[a-z0-9-]{1,})*" .
                             "\.([a-z]{2,}){1}$/";
                    if ( 0 == preg_match ( $regex, $address ) || empty ( $address ) )
                            return false;
                    if ( 0 != preg_match ( "/\\r/", $address ) || preg_match ( "/\\n/", $address ) )
                            return false;
                    // safe
                    return true;
                    break;

            }
        }

        return $var;

    }

    /**
     * Sanitize an array.
     *
     * This method calls sanitize_one() for each element of the given array.
     *
     * Examples:
     * sanitize_array ( $_POST, array ( 'id'=>'number_integer', 'name' => 'string_plain' ) );
     * sanitize_array ( $customArray, array ( 'id'=>'number_integer', 'name' => 'string_plain' ) );
     *
     * @param array $data
     * @param array $whatToKeep
     */
    public static function sanitize_array ( &$pData, $pWhatToKeep )
    {
        try
        {
            Sanitizer::_test_required_parameter ( $pData, 'array' );
            Sanitizer::_test_required_parameter ( $pWhatToKeep, 'array' );
        }
        catch ( SanitizerException $e )
        {
            throw $e;
        }

        $pData = array_intersect_key ( $pData, $pWhatToKeep );

        foreach ( $pData as $key => $value )
        {
            $pData[$key] = Sanitizer::sanitize_one ( $pData[$key] , $pWhatToKeep[$key] );
        }
    }

    /**************************************************************************\
     *
     * Local helper methods.
     *
    \**************************************************************************/

    /**
     * Emulates mysql_real_escape_string.
     *
     * This function emulates mysql_real_escape_string but does not require an
     * active MySQL connection.  Function originates from php.net.
     *
     * @param type $inp
     * @return type
     */
    private static function _mysql_real_escape_string_mimic ( $inp )
    {
        if ( is_array ( $inp ) )
            return array_map ( __METHOD__, $inp );

        if ( !empty ( $inp ) && is_string ( $inp ) )
        {
            return str_replace ( array ( '\\', "\0", "\n", "\r", "'", '"', "\x1a" ),
                                 array ( '\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z' ),
                                 $inp );
        }
        return $inp;
    }

    /**
     * Tests the parameter for a certain type.
     *
     * Given a parameter, this method compares the type of the parameter with a
     * list of types to ensure that the parameter is of this known type.  Five
     * pre-existing types are currently supported: boolean, integer, string,
     * array and mixed.  The mixed type means that the parameter may be of
     * varying type.
     * Throws: S_ERRNUM_UNKNOWN_PHP_TYPE, S_ERRNUM_INVALID_TYPE.
     * @param mixed $pParameter The parameter to be checked.
     * @param string $pType The type the parameter is expected to be.
     */
    private static function _test_parameter_type ( $pParameter, $pType )
    {
        $badType = false;
        switch ( $pType )
        {
            case 'boolean':
                if ( !is_bool ( $pParameter ) ) $badType = true;
                break;

            case 'integer':
                if ( !is_integer ( $pParameter ) ) $badType = true;
                break;

            case 'string':
                if ( !is_string ( $pParameter ) ) $badType = true;
                break;

            case 'array':
                if ( !is_array ( $pParameter ) ) $badType = true;
                break;

            case 'mixed':
                break;

            default:
                throw new SanitizerException ( S_ERRMSG_UNKNOWN_PHP_TYPE, S_ERRNUM_UNKNOWN_PHP_TYPE );
        }

        if ( $badType )
            throw new SanitizerException ( S_ERRMSG_INVALID_TYPE, S_ERRNUM_INVALID_TYPE );
    }

    /**
     * Tests a required parameter for a particular data type.
     *
     * Given a parameter and type, this method ensures the parameter is of the
     * given data type.  To do this, _test_parameter_type() is called.
     * Throws: S_ERRNUM_MISSING_PARAMETER.
     * @param mixed $pParameter The parameter to be checked.
     * @param string $pType The type the parameter is expected to be.
     */
    private static function _test_required_parameter ( $pParameter, $pType )
    {
        if ( !isset ( $pParameter ) )
            throw new SanitizerException ( S_ERRMSG_MISSING_PARAMETER, S_ERRNUM_MISSING_PARAMETER );

        try
        {
            Sanitizer::_test_parameter_type ( $pParameter, $pType );
        }
        catch ( SanitizerException $e )
        {
            throw $e;
        }
    }

    /**
     * Tests an optional parameter for a particular data type.
     *
     * Given a parameter and type, this method ensures the parameter is of the
     * given data type.  To do this, _test_parameter_type() is called.  This
     * method does not throw S_ERRNUM_MISSING_PARAMETER because the parameter
     * is optional.
     * @param mixed $pParameter The parameter to be checked.
     * @param string $pType The type the parameter is expected to be.
     */
    private static function _test_optional_parameter ( $pParameter, $pType )
    {
        if ( isset ( $pParameter ) )
        {
            try
            {
                Sanitizer::_test_parameter_type ( $pParameter, $pType );
            }
            catch ( SanitizerException $e )
            {
                throw $e;
            }
        }
    }

    private static function _dbgecho ( $var )
    {
        echo '<pre>';
        echo '<span style="color: gray">';
        var_dump ( $var );
        echo '</span>';
        echo '</pre>';
    }


}

/**
 * This class is the exception class for the Sanitizer.
 */
class SanitizerException extends Exception
{ }


// <file: Sanitizer.class.php>

?>
