<?php

/**
 * Description of Database
 *
 * @author Jose Arcos
 */
class Database
{

    private $db;   // handle of the db connexion    
    private static $dns = "mysql:host=localhost;dbname=dbprueba";
    private static $user = "root";
    private static $pass = "";
    private static $instance;

    private function __construct()
    {
        try {
            $this->db = new PDO( self::$dns, self::$user, self::$pass );
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch ( PDOException $e ) {
            print "¡Error!: " . $e->getMessage() . "<br/>";
            die();
        }

//        try {
//            # MS SQL Server and Sybase with PDO_DBLIB
//            $DBH = new PDO( "mssql:host=$host;dbname=$dbname, $user, $pass" );
//            $DBH = new PDO( "sybase:host=$host;dbname=$dbname, $user, $pass" );
//
//            # MySQL with PDO_MYSQL
//            $DBH = new PDO( "mysql:host=$host;dbname=$dbname", $user, $pass );
//
//            # SQLite Database
//            $DBH = new PDO( "sqlite:my/database/path/database.db" );
//        } catch ( PDOException $e ) {
//            echo $e->getMessage();
//        }
    }

    public static function getInstance()
    {
        if ( !isset( self::$instance ) ) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    public function create( $table, array $data )
    {
        $names = array_keys( $data );
        $columNames = implode( ', ', $names );
        $valueNames = implode( ', :', $names );

        $STH = $this->db->prepare( "INSERT INTO $table ($columNames) value (:$valueNames)" );
        $STH->execute( $data );
        return $this->db->lastInsertId();
    }

    
    public function read( $table, array $condition = array(), $class_name = '' )
    {
        
        $where = array('1=1');        
        foreach ( $condition as $key => $value ) {            
            $where[] = $key . '=:' . $key;            
        }
        $whereData = implode(' AND ', $where);        
        $STH = $this->db->prepare( "SELECT * FROM `$table` WHERE $whereData" );
        if( !empty( $class_name ) && class_exists( $class_name ) ){
            $STH->setFetchMode( PDO::FETCH_CLASS, $class_name );
        }else{
            $STH->setFetchMode( PDO::FETCH_OBJ );
        }
        $STH->execute( $condition );
        return $STH->fetchAll();
        
    }
    
    public function update( $table, array $data, array $condition )
    {
        $datos = array_merge( $data, $condition );
        $set = array();
        foreach ( $data as $key => $value ) {
            $set[] = $key . '=:' . $key;   
        }
        $where = array();        
        foreach ( $condition as $key => $value ) {            
            $where[] = $key . '=:' . $key;            
        }
        $setData = implode(', ', $set);  
        $whereData = implode(' AND ', $where);  
        $sql = "UPDATE `$table` SET $setData WHERE $whereData;";
        $STH = $this->db->prepare( $sql );
  
        return $STH->execute( $datos );
        
    }
    
    public function delete( $table, array $condition = array() )
    {
     
        $where = array();     
        
        foreach ( $condition as $key => $value ) {            
            $where[] = $key . '=:' . $key;            
        }
   
        $whereData = implode(' AND ', $where);  

        $STH = $this->db->prepare( "DELETE FROM `$table` WHERE $whereData;" );
        return $STH->execute( $condition );
        
    }

  
}
