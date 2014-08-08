<?php
/**
 * Created by PhpStorm.
 * User: barld
 * Date: 6-8-14
 * Time: 23:55
 */

require "factuur.php";

header("Location: ".Factuur::GetFactuurFromXML(60)->SetBetaalObject());