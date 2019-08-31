<?php

namespace database;

use util\config\Config;

class Connections {
    
    /**
     * @var \PDO[] Contenedor de conexiones definidas en 'database_config.php'
     */
    private static $connections = array();
    
    /**
     * Devuelve una conexion a una fuente de datos
     * las conexiones se deben definir en el archivo database_config.php
     * @param string $connectionKey Nombre de la conexion
     * @return \PDO
     */
    public static function getConnection( $connectionKey = 'default' ) {
        if ( !self::connectionWasInitialized($connectionKey) ) {
            $configuration = Config::get($connectionKey, 'database_config');
            
            if ( !empty($configuration) ) {
                $dsn = $configuration['dsn'];
                $user = $configuration['user'];
                $password = $configuration['password'];

                $connection = new \PDO($dsn, $user, $password, array(
                    \PDO::ATTR_PERSISTENT => false
                ));

                $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

                /*if ( strpos($connection->getAttribute(\PDO::ATTR_CLIENT_VERSION), 'mysqlnd') === false ) {
                    Logger()->warning('PDO MySQLnd is not enabled');
                }*/

                self::$connections[$connectionKey] = $connection;
            }
        }

        return self::$connections[$connectionKey];
    }
    
    private static function connectionWasInitialized( $connectionKey ) {
        if ( array_key_exists($connectionKey, self::$connections) && isset(self::$connections[$connectionKey]) ) {
            return TRUE;
        }

        return FALSE;
    }
}