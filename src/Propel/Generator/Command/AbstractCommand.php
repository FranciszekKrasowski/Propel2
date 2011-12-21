<?php

/**
 * This file is part of the Propel package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Propel\Generator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
abstract class AbstractCommand extends Command
{
    const DEFAULT_INPUT_DIRECTORY   = '.';

    const DEFAULT_PLATFORM          = 'MysqlPlatform';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('input-dir',    null, InputOption::VALUE_REQUIRED,  'The input directory', self::DEFAULT_INPUT_DIRECTORY),
                new InputOption('platform',     null, InputOption::VALUE_REQUIRED,  'The platform', self::DEFAULT_PLATFORM),
            ));
    }

    protected function getBuildProperties($file)
    {
        $properties = array();

        if (false === $lines = @file($file)) {
            throw new \Exception(sprintf('Unable to parse contents of "%s".', $file));
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ('' == $line || in_array($line[0], array('#', ';'))) {
                continue;
            }

            $pos = strpos($line, '=');
            $properties[trim(substr($line, 0, $pos))] = trim(substr($line, $pos + 1));
        }

        return $properties;
    }

    protected function getSchemas(InputInterface $input)
    {
        $finder = new Finder();
        return $finder
            ->name('*schema.xml')
            ->in($input->getOption('input-dir'))
            ->depth(0)
            ->files()
            ;
    }
}
