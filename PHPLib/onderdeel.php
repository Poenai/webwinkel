<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 24-7-14
 * Time: 15:37
 */

class Onderdeel {
    public $id;
    public $aantal;

    public function __construct($id, $aantal)
    {
        $this->id = $id;
        $this->aantal = $aantal;
    }
} 