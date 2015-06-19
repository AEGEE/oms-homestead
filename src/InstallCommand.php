<?php

namespace Laravel\Homestead;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    /**
     * The base path of the Laravel installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The name of the project folder.
     *
     * @var string
     */
    protected $projectName;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('install')
            ->setDescription('Install Homestead into the current project')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The name the virtual machine.')
            ->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname the virtual machine.')
            ->addOption('after', null, InputOption::VALUE_NONE, 'Determines if the after.sh file is created.', false)
            ->addOption('aliases', null, InputOption::VALUE_NONE, 'Determines if the aliases file is created.', false);

        $this->basePath = getcwd();
        $this->projectName = basename(getcwd());
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        copy(__DIR__.'/stubs/LocalizedVagrantfile', $this->basePath.'/Vagrantfile');
        copy(__DIR__.'/stubs/Homestead.yaml', $this->basePath.'/Homestead.yaml');

        if ($input->getOption('after')) {
            copy(__DIR__.'/stubs/after.sh', $this->basePath.'/after.sh');
        }

        if ($input->getOption('aliases')) {
            copy(__DIR__.'/stubs/aliases', $this->basePath.'/aliases');
        }

        if ($input->getOption('name')) {
            $this->updateName($input->getOption('name'));
        }

        if ($input->getOption('hostname')) {
            $this->updateHostName($input->getOption('hostname'));
        }

        $this->configurePaths();

        $output->writeln('Homestead Installed!');
    }

    /**
     * Update paths in Homestead.yaml
     */
    protected function configurePaths()
    {
        $yaml = str_replace(
            "- map: ~/Code", "- map: ".$this->basePath, $this->getHomesteadFile()
        );

        $yaml = str_replace(
            "to: /home/vagrant/Code", "to: /home/vagrant/".$this->projectName, $yaml
        );

        // Fix path to the public folder (sites: to:)
        $yaml = str_replace(
            $this->projectName."/Laravel", $this->projectName, $yaml
        );

        file_put_contents($this->basePath.'/Homestead.yaml', $yaml);
    }

    /**
     * Update the "name" variable of the Homestead.yaml file.
     *
     * VirtualBox requires a unique name for each virtual machine.
     *
     * @param  string  $name
     * @return void
     */
    protected function updateName($name)
    {
        file_put_contents($this->basePath.'/Homestead.yaml', str_replace(
            "cpus: 1", "cpus: 1".PHP_EOL."name: ".$name, $this->getHomesteadFile()
        ));
    }

    /**
     * Set the virtual machine's hostname setting in the Homstead.yaml file.
     *
     * @param  string  $hostname
     * @return void
     */
    protected function updateHostName($hostname)
    {
        file_put_contents($this->basePath.'/Homestead.yaml', str_replace(
            "cpus: 1", "cpus: 1".PHP_EOL."hostname: ".$hostname, $this->getHomesteadFile()
        ));
    }

    /**
     * Get the contents of the Homestead.yaml file.
     *
     * @return string
     */
    protected function getHomesteadFile()
    {
        return file_get_contents($this->basePath.'/Homestead.yaml');
    }
}