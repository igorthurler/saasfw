<?php

abstract class PfwConnection
{
    static function getDriver($name = 'pfwdriver', $driverCode) {
        $driver = DbDriverFactory::getDriver($driverCode);
        DAOFactory::addDSN($name, $driver->configure(PFW_DBHOST, PFW_DBNAME, PFW_DBUSER, PFW_DBPASS));
        return $driver;
    }
}