<?php
declare(strict_types=1);

namespace IkonizerCore\Console\Commands\Traits;

use IkonizerCore\Base\Exception\BaseNoValueException;
use IkonizerCore\Console\Exception\MakeCommandFileAlreadyExistException;
use IkonizerCore\Base\Exception\BaseInvalidArgumentException;
use IkonizerCore\Base\Exception\BaseLogicException;
use IkonizerCore\Base\Exception\BaseRuntimeException;
use IkonizerCore\Utility\Files;
use IkonizerCore\Utility\Stringify;
use IkonizerCore\Utility\Utilities;
use RingCentral\Tests\Psr7\Str;

trait MakeMigrationTrait
{

    /**
     * @param string $option
     */
    private function resolveMigrationFromOptions(string $option)
    {
        if (empty($option)) {
            throw new BaseNoValueException('Please specify the required argument');
        }
        if (empty($schemas = $this->getSchemaFiles())) {
            throw new BaseInvalidArgumentException('Your schema directory is currently empty. Theres nothing to migrate.');
        }
        $schemaName = Stringify::studlyCaps($option . 'Schema');
        if (is_array($schemas)) {
            foreach ($schemas as $schema) {
                if (!str_contains($schema, $schemaName)) {
                    $optionSchema = $option . '_schema';
                    throw new BaseInvalidArgumentException(
                        sprintf(
                            'Sorry there is no schema class found for %s. Have you created this schema class using the magma:make %s command. This must be done first in order to create a migration file from it.',
                            $option, $optionSchema)
                    );
                }
            }
        }
        return $this->resolveMigration($option, $schemaName);
    }

    /**
     * @param mixed $option
     * @param string $schemaName
     */
    private function resolveMigration(mixed $option, string $schemaName)
    {

    }

}