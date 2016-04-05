<?php
declare(strict_types = 1);

namespace Bnowak\CodeQualityChecker;

use Bnowak\CodeQualityChecker\Command\PhpLint;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Code quality checker application
 *
 * @author Bartłomiej Nowak <barteknowak90@gmail.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Code Quality Checker');
        $this->add(new PhpLint());
    }
}
