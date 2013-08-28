<?php
/**
 * Test.php
 * @author Tom
 * @since 28/08/13
 */
namespace Model;

use Framework\Database;

class Test {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function doSomething()
    {
        $sel = $this->db->select('test')->where('name', '=', 'test');
        return $this->db->query($sel)->fetchAll();
    }

}