<?php

namespace LDL\Http\Api\Console\Command;

use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfig;
use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfigInterface;
use LDL\Http\Api\Service\Application\Application\Builder\ApplicationBuilderService;
use LDL\Http\Api\Service\Application\Application\Writer\ApplicationWriterService;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateApplicationCommand extends SymfonyCommand
{
    public const EXIT_SUCCESS = 0;
    public const EXIT_ERROR = 1;

    /**
     * @var ApplicationConfigInterface
     */
    private $defaultApplicationConfig;

    public function configure(): void
    {
        $this->defaultApplicationConfig = ApplicationConfig::fromArray([]);

        $this->setName('application:create')
            ->setDescription('Create a new application')
            ->addOption(
                'wizard-interactive',
                'i',
                InputOption::VALUE_NONE,
                'Set application parameters in an interactive way'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $this->build($input, $output);
            return self::EXIT_SUCCESS;

        }catch(\Exception $e){

            $output->writeln("<error>{$e->getMessage()}</error>");
            return self::EXIT_ERROR;

        }
    }

    private function build(InputInterface $input, OutputInterface $output)
    {
        $interactive = $input->getOption('wizard-interactive');

        try{
            $applicationConfig = false === $interactive ? $this->defaultApplicationConfig : $this->createApplicationConfig($input, $output);

            ApplicationBuilderService::build($applicationConfig);

            $writer = new ApplicationWriterService();
            $writer->write($applicationConfig);

            $output->writeln("\n<info>Application created successfully</info>\n");

        }catch(\Exception $e){

            $output->writeln("\n<error>Creation Failed!</error>\n");
            $output->writeln("\n<error>{$e->getMessage()}</error>\n");

            return;
        }
    }

    private function createApplicationConfig(InputInterface $input, OutputInterface $output)
    {
        return ApplicationConfig::fromArray([
            'name' => $this->askName($input, $output),
            'prefix' => $this->askPrefix($input, $output),
            'source' => $this->askSource($input, $output),
            'dispatcher' => [
                'folder' => $this->askDispatcherFolder($input, $output)
            ],
            'endpoint' => [
                'folder' => $this->askEndpointFolder($input, $output)
            ],
            'schema' => [
                'folder' => $this->askSchemaFolder($input, $output)
            ]
        ]);
    }

    private function askName(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getName()
        );

        $output->writeln($questionText);

        $question = new Question('Application name: ', $this->defaultApplicationConfig->getName());
        return $helper->ask($input, $output, $question);
    }

    private function askPrefix(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getPrefix()
        );

        $output->writeln($questionText);

        $question = new Question('Application prefix: ', $this->defaultApplicationConfig->getPrefix());
        return $helper->ask($input, $output, $question);
    }

    private function askSource(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getSource()
        );

        $output->writeln($questionText);

        $question = new Question('Application path: ', $this->defaultApplicationConfig->getSource());
        return $helper->ask($input, $output, $question);
    }

    private function askDispatcherFolder(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getDispatcherFolderName()
        );

        $output->writeln($questionText);

        $question = new Question('Dispatcher folder name: ', $this->defaultApplicationConfig->getDispatcherFolderName());
        return $helper->ask($input, $output, $question);
    }

    private function askEndpointFolder(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getEndpointFolderName()
        );

        $output->writeln($questionText);

        $question = new Question('Endpoint folder name: ', $this->defaultApplicationConfig->getEndpointFolderName());
        return $helper->ask($input, $output, $question);
    }

    private function askSchemaFolder(InputInterface $input, OutputInterface $output): string
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultApplicationConfig->getSchemaFolderName()
        );

        $output->writeln($questionText);

        $question = new Question('Schema folder name: ', $this->defaultApplicationConfig->getSchemaFolderName());
        return $helper->ask($input, $output, $question);
    }
}