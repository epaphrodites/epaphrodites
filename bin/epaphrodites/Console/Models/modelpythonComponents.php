<?php

namespace Epaphrodites\epaphrodites\Console\Models;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epaphrodites\epaphrodites\Console\Setting\settingpythonComponents;

class modelpythonComponents extends settingpythonComponents
{
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(
        InputInterface $input, 
        OutputInterface $output
    ){
        $result = $this->executeCommands($this->pipCommands(), $output);

        if ($result === 0) {
            $output->writeln('<info>All commands executed successfully ✅</info>');
            return static::SUCCESS;
        } else {
            $output->writeln('<error>An error occurred while executing commands ❌</error>');
            return static::FAILURE;
        }
    }

    /**
     * Execute the given commands sequentially
     * 
     * @param array $commands
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    private function executeCommands(
        array $commands, 
        OutputInterface $output
    ): int{
        foreach ($commands as $command => $messages) {

            $output->writeln("Executing command: $command");
            $result = shell_exec($command);
            $output->writeln($result);

            if (strpos($result, 'already satisfied') !== false) {
                $output->writeln("<error>{$messages['already_installed']}</error>");
            } else {
                $output->writeln("<info>{$messages['success']}</info>");
            }
            $output->writeln('');
        }

        return 0;
    }

    /**
     * Get the list of pip commands to execute
     * 
     * @return array
     */
    private function pipCommands(): array
    {
        return [
            'pip install numpy' => [
                'success' => 'NumPy installation completed',
                'already_installed' => 'NumPy is already installed'
            ],
            'pip install pandas' => [
                'success' => 'Pandas installation completed',
                'already_installed' => 'Pandas is already installed'
            ],
            'pip install pycryptodome' => [
                'success' => 'pycryptodome installation completed',
                'already_installed' => 'pycryptodome is already installed'
            ],
            'pip install pytesseract' => [
                'success' => 'pytesseract installation completed',
                'already_installed' => 'pytesseract is already installed'
            ],
            'pip install PyPDF2' => [
                'success' => 'PyPDF2 installation completed',
                'already_installed' => 'PyPDF2 is already installed'
            ],
        ];
    }
}