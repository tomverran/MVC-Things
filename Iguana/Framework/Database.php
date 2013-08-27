<?php
/**
 * Database.php
 * @author Tom
 * @since 27/08/13
 */
namespace Framework;


/**
 * Class Database
 * @package Framework
 */
class Database {

    use Configurable;

    /**
     * Get a database connection
     */
    public static function factory()
    {
        $dsn = self::get('driver') . ':host=' . self::get('host') . ';dbname=' . self::get('dbname');
        return new \PDO($dsn, self::get('user'), self::get('password'));
    }
}