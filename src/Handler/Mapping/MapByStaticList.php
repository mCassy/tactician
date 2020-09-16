<?php
declare(strict_types=1);

namespace League\Tactician\Handler\Mapping;

use function array_key_exists;

/**
 * This mapping is useful when you're working with a compiled list or a legacy
 * app without a consistent naming convention.
 *
 * The mapping array should be in the following format:
 *
 *      [
 *          SomeCommand::class => [SomeHandler::class, 'handle'],
 *          OtherCommand::class => [WhateverHandler::class, 'handleOtherCommand'],
 *          ...
 *      ]
 */
final class MapByStaticList implements CommandToHandlerMapping
{
    /** @var array<string, array<string>> */
    private $mapping;

    /** @param array<string, array<string>> $mapping */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public function mapCommandToHandler(string $commandFQCN): MethodToCall
    {
        if (! \array_key_exists($commandFQCN, $this->mapping)) {
            throw FailedToMapCommand::className($commandFQCN);
        }

        return new MethodToCall(
            $this->mapping[$commandFQCN][0],
            $this->mapping[$commandFQCN][1]
        );
    }
}
