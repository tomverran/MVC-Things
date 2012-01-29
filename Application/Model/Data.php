<?php
namespace Application\Model;
/**
 * An example model. No, you get no DB connection.
 * I advise using Zend_Db or Propel or Doctrine or NotORM or whatever if you want one.
 */
class Data {

    public function getData() {
        return array('message'=>'<h3>XSS!</h3>hello, world!');
    }

}
