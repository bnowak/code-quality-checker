<?php
declare(strict_types = 1);

namespace Bnowak\CodeQualityChecker\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * AbstractCommand
 *
 * @author Bartłomiej Nowak <barteknowak90@gmail.com>
 */
class AbstractCommand extends Command
{
    protected function getFilesPaths(InputInterface $input)
    {
        $fileArguments = $input->getArgument('file');
        if (count($fileArguments) === 0) {
            $modifiedFileList = 'git diff HEAD --name-only --diff-filter=ACMRTU';
            $untrackedFileList = 'git ls-files -o --exclude-standard';
            $process = new Process("($modifiedFileList && $untrackedFileList) | sort");
            $process->run();
            
            if (false === $process->isSuccessful()) {
                throw new Exception($process->getErrorOutput());
            }
            
            $fileArguments = array_filter(explode("\n", $process->getOutput()));
        }
        
        $filePaths = array();
        foreach ($fileArguments as $fileArgument) {
            $fileArgumentPath = getcwd().'/'.$fileArgument;
            if (is_file($fileArgumentPath)) {
                $filePaths[] = $fileArgumentPath;
            } elseif (is_dir($fileArgumentPath)) {
                $finder = new Finder();
                $finder->files()->in($fileArgumentPath);
                foreach ($finder as $filePath) {
                    $filePaths[] = $filePath;
                }
            } else {
                throw new Exception(sprintf('File or directory "%s" does not exist', $fileArgumentPath));
            }
        }
        
        return $filePaths;
    }
}
