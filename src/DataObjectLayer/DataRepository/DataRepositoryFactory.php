<?php

declare(strict_types=1);

namespace IkonizerCore\DataObjectLayer\DataRepository;

use IkonizerCore\Utility\Yaml;
use IkonizerCore\DataObjectLayer\DataLayerFactory;
use IkonizerCore\DataObjectLayer\DataLayerEnvironment;
use IkonizerCore\DataObjectLayer\DataLayerConfiguration;
use IkonizerCore\DataObjectLayer\Exception\DataLayerUnexpectedValueException;
use Symfony\Component\Dotenv\Dotenv;

class DataRepositoryFactory
{

    /** @var string */
    protected string $tableSchema;

    /** @var string */
    protected string $tableSchemaID;

    /** @var string */
    protected string $crudIdentifier;

    /**
     * Main class constructor
     *
     * @param string $crudIdentifier
     * @param string $tableSchema
     * @param string $tableSchemaID
     */
    public function __construct(string $crudIdentifier, string $tableSchema, string $tableSchemaID)
    {
        $this->crudIdentifier = $crudIdentifier;
        $this->tableSchema = $tableSchema;
        $this->tableSchemaID = $tableSchemaID;
    }

    /**
     * Create the DataRepository Object. Which is the middle layer that interacts with
     * the application using this framework. The data repository object will have 
     * the required dependency injected by default. Which is the data layer facade object
     * which is simple passing in the entity manager object which expose the crud methods
     *
     * @param string $dataRepositoryString
     * @param array|null $dataLayerConfiguration
     * @return DataRepositoryInterface
     * @throws DataLayerUnexpectedValueException
     */
    public function create(string $dataRepositoryString, ?array $dataLayerConfiguration = null) : DataRepositoryInterface
    {
        
        $dataRepositoryObject = new $dataRepositoryString($this->buildEntityManager($dataLayerConfiguration));
        if (!$dataRepositoryObject instanceof DataRepositoryInterface ) {
            throw new DataLayerUnexpectedValueException($dataRepositoryString . ' is not a valid repository object');
        }
        return $dataRepositoryObject;
    }

    /**
     * Build entity manager which creates the data layer factory and passing in the
     * environment configuration array and symfony dotenv component. Which is used 
     * to set the database environment config.
     *
     * @param array|null $dataLayerConfiguration
     * @return Object
     */
    public function buildEntityManager(?array $dataLayerConfiguration = null) : Object
    {
        $dataLayerEnvironment = new DataLayerEnvironment(
            new DataLayerConfiguration(
            Dotenv::class,
            ($dataLayerConfiguration !==null) ? $dataLayerConfiguration : NULL,
            ),
            Yaml::file('app')['database']['default_driver'] /* second argument */

        );
        $factory = new DataLayerFactory($dataLayerEnvironment, $this->tableSchema, $this->tableSchemaID);
        if ($factory) {
            return $factory->dataEntityManagerObject();
        }

    }

}
