<?php
declare(strict_types=1);

namespace IkonizerCore\DataObjectLayer\Drivers;

use PDO;
use PDOException;
use IkonizerCore\DataObjectLayer\Exception\DataLayerException;
use IkonizerCore\DataObjectLayer\Exception\DataLayerInvalidArgumentException;

class PgsqlDatabaseConnection extends AbstractDatabaseDriver
{

    /** @var string $driver */
    protected const PDO_DRIVER = 'pgsql';
    private object $environment;
    private string $pdoDriver;

    /**
     * Class constructor. piping the class properties to the constructor
     * method. The constructor will throw an exception if the database driver
     * doesn't match the class database driver.
     *
     * @param object $environment
     * @param string $pdoDriver
     * @return void
     */
    public function __construct(object $environment, string $pdoDriver)
    {
        $this->environment = $environment;
        $this->pdoDriver = $pdoDriver;
        if (self::PDO_DRIVER !== $this->pdoDriver) {
            throw new DataLayerInvalidArgumentException(
                $pdoDriver . ' Invalid database driver pass requires ' . self::PDO_DRIVER . ' driver option to make your connection.'
            );
        }
    }

    /**
     * Opens a new Pgsql database connection
     *
     * @return PDO
     * @throws DataLayerException
     */
    public function open(): PDO
    {
        try {
            return new PDO(
                $this->credential->getDsn(),
                $this->credential->getDbUsername(),
                $this->credential->getDbPassword(),
                $this->params
            );
        } catch(PDOException $e) {
            throw new DataLayerException($e->getMessage(), (int)$e->getCode());
        }

    }

}
