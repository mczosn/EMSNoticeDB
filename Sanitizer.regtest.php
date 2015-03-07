<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Sanitizer.class Regression Test</title>
<style>
body { font-family: sans-serif; }
p { font-size: small; color: cornflowerblue; }
h1 { color: #0B2161; }
h2 { color: #FACC2E; }
.failed { color: red; }
.passed { color: green; }
.message { color: #BDBDBD; }
</style>
</head>

<body>

<h1>Sanitizer.class Regression Test</h1>

<?php

require_once 'regressiontestlib.php';
require_once 'Sanitizer.class.php';

$G_regressionTestNum = 1;

function do_regression_test_number_integer()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on integers.' );

    start_unit_test ( 'UT01', 'sanitize_one(integer) correctly.' );
    $i = 7;
    $result = Sanitizer::sanitize_one ( $i, 'number_integer' );
    $success = print_pass_or_fail ( $result, 7, 'Result should be 7.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT02', 'sanitize_one(integer) with string number.' );
    $i = '7';
    $result = Sanitizer::sanitize_one ( $i, 'number_integer' );
    $success = print_pass_or_fail ( $result, 7, 'Result should be 7.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT03', 'sanitize_one(integer) with string non-number.' );
    $i = 'cat';
    $result = Sanitizer::sanitize_one ( $i, 'number_integer' );
    $success = print_pass_or_fail ( $result, 0, 'Result should be 0.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT04', 'sanitize_one(integer) with string non-number and number.' );
    $i = 'cat7';
    $result = Sanitizer::sanitize_one ( $i, 'number_integer' );
    $success = print_pass_or_fail ( $result, 0, 'Result should be 0.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_number_float()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on floats.' );

    start_unit_test ( 'UT01', 'sanitize_one(float) correctly.' );
    $f = 7.7;
    $result = Sanitizer::sanitize_one ( $f, 'number_float' );
    $success = print_pass_or_fail ( $result, 7.7, 'Result should be 7.7.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT02', 'sanitize_one(float) with string number.' );
    $f = '7.7';
    $result = Sanitizer::sanitize_one ( $f, 'number_float' );
    $success = print_pass_or_fail ( $result, 7.7, 'Result should be 7.7.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT03', 'sanitize_one(float) with string non-number.' );
    $f = 'cat';
    $result = Sanitizer::sanitize_one ( $f, 'number_float' );
    $success = print_pass_or_fail ( $result, 0.0, 'Result should be 0.0.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT04', 'sanitize_one(float) with string non-number and number.' );
    $f = 'cat7.7';
    $result = Sanitizer::sanitize_one ( $f, 'number_float' );
    $success = print_pass_or_fail ( $result, 0.0, 'Result should be 0.0.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_plain()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on plain strings.' );

    $orig_string = 'Four score and seven years ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_plain) correctly.' );
    $s = $orig_string;
    $result = Sanitizer::sanitize_one ( $s, 'string_plain' );
    $success = print_pass_or_fail ( $result, $orig_string, 'Result should be the original string.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT02', 'sanitize_one(string_plain) correctly.' );
    $s = '  ' . $orig_string . '          ';
    $result = Sanitizer::sanitize_one ( $s, 'string_plain' );
    $success = print_pass_or_fail ( $result, $orig_string, 'Result should be the original string trimmed.', true, false, true );
    end_unit_test();

    start_unit_test ( 'UT03', 'sanitize_one(string_plain) correctly.' );
    $s = '  ' . $orig_string . '          ';
    $result = Sanitizer::sanitize_one ( $s, 'string_plain', false );
    $success = print_pass_or_fail ( $result, $s, 'Result should be the original string not trimmed.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT04', 'sanitize_one(string_plain) with double quoted string.' );
    // htmlentities with ENT_NOQUOTES should keep quotes in the string
    $s = '"' . $orig_string . '"';
    $result = Sanitizer::sanitize_one ( $s, 'string_plain' );
    $success = print_pass_or_fail ( $result, $s, 'Result should be the original string.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT05', 'sanitize_one(string_plain) with single quoted string.' );
    // htmlentities with ENT_NOQUOTES should keep quotes in the string
    $s = "'" . $orig_string . "'";
    $result = Sanitizer::sanitize_one ( $s, 'string_plain' );
    $success = print_pass_or_fail ( $result, $s, 'Result should be the original string.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT06', 'sanitize_one(string_plain) with HTML in string.' );
    // Should convert the < and > to entities and keep the quotes
    $s = '<strong>"' . $orig_string . '"</strong>';
    $result = Sanitizer::sanitize_one ( $s, 'string_plain' );
    $success = print_pass_or_fail ( $result,
                                    '&lt;strong&gt;"Four score and seven years ago..."&lt;/strong&gt;',
                                    'Result should convert the < and > to entities and keep the quotes.',
                                    false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_nohtml()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on no-HTML strings.' );

    $orig_string = 'Four score and seven years ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_nohtml) correctly.' );
    $s = $orig_string;
    $result = Sanitizer::sanitize_one ( $s, 'string_nohtml' );
    $success = print_pass_or_fail ( $result, $s, 'Result should be the original string.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT02', 'sanitize_one(string_nohtml) with double quoted string.' );
    // htmlentities with ENT_QUOTES should convert quotes in the string
    $os = '"' . $orig_string . '"';
    $sb = '&quot;' . $orig_string . '&quot;';
    $result = Sanitizer::sanitize_one ( $os, 'string_nohtml' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should convert the double quote to an entity.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT03', 'sanitize_one(string_nohtml) with single quoted string.' );
    // htmlentities with ENT_QUOTES should convert quotes in the string
    $os = "'" . $orig_string . "'";
    $sb = '&#039;' . $orig_string . '&#039;';
    $result = Sanitizer::sanitize_one ( $os, 'string_nohtml' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should convert the single quote to an entity.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT04', 'sanitize_one(string_nohtml) with HTML in string.' );
    // Should convert the < and > to entities and keep the quotes
    $os = '<strong>"' . $orig_string . '"</strong>';
    $sb = '&lt;strong&gt;&quot;' . $orig_string . '&quot;&lt;/strong&gt;';
    $result = Sanitizer::sanitize_one ( $os, 'string_nohtml' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should convert the quote, < and > to entities.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_ucwords()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on ucwords strings.' );

    $orig_string = 'Four score and seven years ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_ucwords) correctly.' );
    $os = $orig_string;
    $sb = 'Four Score And Seven Years Ago...';
    $result = Sanitizer::sanitize_one ( $os, 'string_ucwords' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should have words capitalized.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_ucfirst()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on ucfirst strings.' );

    $orig_string = 'four score and seven years ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_ucfirst) correctly.' );
    $os = $orig_string;
    $sb = 'Four score and seven years ago...';
    $result = Sanitizer::sanitize_one ( $os, 'string_ucfirst' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should have first word capitalized.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_lower()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on lowercase strings.' );

    $orig_string = 'FOUR score AND seven YEARS ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_lower) correctly.' );
    $os = $orig_string;
    $sb = 'four score and seven years ago...';
    $result = Sanitizer::sanitize_one ( $os, 'string_lower' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be completely lowercase.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_upper()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on uppercase strings.' );

    $orig_string = 'FOUR score AND seven YEARS ago...';

    start_unit_test ( 'UT01', 'sanitize_one(string_upper) correctly.' );
    $os = $orig_string;
    $sb = 'FOUR SCORE AND SEVEN YEARS AGO...';
    $result = Sanitizer::sanitize_one ( $os, 'string_upper' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be completely uppercase.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_urlencode()
{
    // urlencoding test comes from albionresearch.com, viewed 2011-09-10.
    // http://www.albionresearch.com/misc/urlencode.php

    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on urlencode strings.' );

    $orig_string = '"Aardvarks lurk, OK?"';

    start_unit_test ( 'UT01', 'sanitize_one(string_urlencode) correctly.' );
    $os = $orig_string;
    $sb = '%22Aardvarks+lurk%2C+OK%3F%22';
    $result = Sanitizer::sanitize_one ( $os, 'string_urlencode' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be url encoded.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_dbsafe()
{
    // SQL injection tests come from tizag.com, viewed 2011-09-10.
    // http://www.tizag.com/mysqlTutorial/mysql-php-sql-injection.php

    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer on database-safe strings.' );

    start_unit_test ( 'UT01', 'sanitize_one(string_dbsafe) correctly - easy.' );
    $sql_injection = "' OR 1'";
    $sql_string = "SELECT * FROM customers WHERE username = '$sql_injection'";
    $sb = "SELECT * FROM customers WHERE username = \\'\\' OR 1\\'\\'";
    $result = Sanitizer::sanitize_one ( $sql_string, 'string_dbsafe' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be database-safe.', false, false, true );
    end_unit_test();

    start_unit_test ( 'UT02', 'sanitize_one(string_dbsafe) correctly - more serious.' );
    $sql_injection = "'; DELETE FROM customers WHERE 1 or username = '";
    $sql_string = "SELECT * FROM customers WHERE username = '$sql_injection'";
    $sb = "SELECT * FROM customers WHERE username = \\'\\'; DELETE FROM customers WHERE 1 or username = \\'\\'";
    $result = Sanitizer::sanitize_one ( $sql_string, 'string_dbsafe' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be database-safe.', false, false, true );
    end_unit_test();

    end_regression_test();
}

function do_regression_test_string_multiple()
{
    global $G_regressionTestNum;

    $regtestId = sprintf ( "RT%03d", $G_regressionTestNum++ );

    start_regression_test ( $regtestId, 'Test Sanitizer with multiple types.' );

    start_unit_test ( 'UT01', 'sanitize_one(multiple) correctly.' );
    $htmlWithUrl = '<a href="cart/phpcart.php?action=add&id=112233&descr=Strawberries & cream">';
    $sb = '%3Ca+href%3D%22cart%2Fphpcart.php%3Faction%3Dadd%26id%3D112233%26descr%3DStrawberries+%26+cream%22%3E';
    $result = Sanitizer::sanitize_one ( $htmlWithUrl, 'string_urlencode, string_nohtml' );
    $success = print_pass_or_fail ( $result, $sb, 'Result should be have no HTML and url encoded.', false, false, true );
    end_unit_test();

    end_regression_test();
}

// ----------------------------------------------------------------------------
// <main>
// ----------------------------------------------------------------------------

// Test sanitization of an integer.
echo '<hr />';
do_regression_test_number_integer();

// Test sanitization of a float.
echo '<hr />';
do_regression_test_number_float();

// Test sanitization of a plain string.
echo '<hr />';
do_regression_test_string_plain();

// Test sanitization of a no-HTML string.
echo '<hr />';
do_regression_test_string_nohtml();

// Test sanitization of a string making uppercase words.
echo '<hr />';
do_regression_test_string_ucwords();

// Test sanitization of a string making the first word uppercase.
echo '<hr />';
do_regression_test_string_ucfirst();

// Test sanitization of a string making the first word uppercase.
echo '<hr />';
do_regression_test_string_lower();

// Test sanitization of a string making the first word uppercase.
echo '<hr />';
do_regression_test_string_upper();

// Test sanitization of a urlencoded string.
echo '<hr />';
do_regression_test_string_urlencode();

// Test sanitization of a database-safe string.
echo '<hr />';
do_regression_test_string_dbsafe();

// Test sanitization of a database-safe string.
echo '<hr />';
do_regression_test_string_multiple();

// ----------------------------------------------------------------------------
// </main>
// ----------------------------------------------------------------------------

?>

</body>
</html>

