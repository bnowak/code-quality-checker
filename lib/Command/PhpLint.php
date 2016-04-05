<?php
declare(strict_types = 1);

namespace Bnowak\CodeQualityChecker\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * PhpLint
 * Check PHP file syntax
 *
 * @author Bartłomiej Nowak <barteknowak90@gmail.com>
 */
class PhpLint extends AbstractCommand
{
    public function __construct()
    {
        parent::__construct('php:lint');
    }
    
    protected function configure()
    {
        $this->setDescription('Check PHP file syntax');
        $this->addArgument(
            'file',
            InputArgument::IS_ARRAY,
            'One or more files and/or directories to check, if none git modified files in current repo will be check'
        );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePaths = $this->getFilesPaths($input);
        
        if (empty($filePaths)) {
            $output->writeln('<comment>No file argument passed and no git changes detected</comment>');
        }
        
        foreach ($filePaths as $filePath) {
            $processBuilder = new ProcessBuilder(array('php', '-l', $filePath));
            $process = $processBuilder->getProcess();
            $process->run();
            
            if (false === $process->isSuccessful()) {
                $output->writeln(sprintf('<error>%s</error>', trim($process->getErrorOutput())));
            }
        }
    }
}
