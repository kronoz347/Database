<?php
include './Database.php';
function p( $resultado, $title = '' ){
    if( !empty( $title ) ){
        echo '<h2>' . $title . '</h2>';
    }
    echo '<pre>';
    print_r( $resultado );
    echo '</pre>';
}

$db = Database::getInstance();

//$userId = $db->create( 'usuarios', array('username' => 'Maria', 'password' => md5( 'maria' ), 'email' => 'maria@mail.com' ) );
//
//$user = $db->read( 'usuarios', array( 'id' => $userId ) );
//
//p($user, 'Read');
//
//$useru = $db->update( 'usuarios', ['username' => 'Laura'] , [ 'id' => $userId] );
//echo '<h2>Update</h2>';
//var_dump($useru);

$userd = $db->delete( 'usuarios', [ 'id' => 26] );
echo '<h2>Delete</h2>';
var_dump($userd);

$usuarios = $db->read( 'usuarios');

p($usuarios);
