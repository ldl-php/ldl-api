<?php

namespace LDL\Http\Api\Console\Command;

use LDL\Http\Api\Service\Application\Application\Reader\ApplicationReaderService;
use LDL\Http\Api\Service\Application\Endpoint\Config\EndpointConfig;
use LDL\Http\Api\Service\Application\Endpoint\Config\EndpointConfigInterface;
use LDL\Http\Api\Service\Application\Endpoint\Writer\EndpointWriterService;
use LDL\Http\Api\Service\Application\Endpoint\Writer\Options\EndpointWriterOptions;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateEndpointCommand extends SymfonyCommand
{
    public const EXIT_SUCCESS = 0;
    public const EXIT_ERROR = 1;

    /**
     * @var EndpointConfigInterface
     */
    private $defaultEndpointConfig;

    public function configure(): void
    {
        $this->defaultEndpointConfig = EndpointConfig::fromArray([]);

        $this->setName('application:endpoint:create')
            ->setDescription('Create a new endpoint for application')
            ->addArgument(
                'application-config-path',
                InputArgument::REQUIRED,
                'Path of the application config',
                )
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
            $applicationConfig = ApplicationReaderService::read($input->getArgument('application-config-path'));

            $endpointConfig = false === $interactive ? $this->defaultEndpointConfig : $this->createEndpointConfig($input, $output);

            $endpointWriterOptions = EndpointWriterOptions::fromArray([
               'source' => sprintf(
                   '%s%s%s',
                   $applicationConfig->getSource(),
                   DIRECTORY_SEPARATOR,
                   $applicationConfig->getEndpointFolderName()
               )
            ]);

            $endpointWriter = new EndpointWriterService($endpointWriterOptions);
            $endpointWriter->write($endpointConfig);

            $output->writeln("\n<info>Application endpoint created successfully</info>\n");

        }catch(\Exception $e){

            $output->writeln("\n<error>Endpoint creation failed!</error>\n");
            $output->writeln("\n<error>{$e->getMessage()}</error>\n");

            return;
        }
    }

    private function createEndpointConfig(InputInterface $input, OutputInterface $output)
    {
        return EndpointConfig::fromArray([
            'version' => $this->askVersion($input, $output),
            'url' => [
                'prefix' => $this->askUrlPrefix($input, $output),
                'authentication' => [
                    'type' => $this->askAuthenticationType($input, $output)
                ]
            ],
            'name' => $this->askName($input, $output),
            'description' => $this->askDescription($input, $output),
            'dispatcher' => [
                'class' => $this->askDispatcherClass($input, $output)
            ],
            'request' => [
                'method' => $this->askRequestMethod($input, $output),
                'headers' => [
                    'schema' => [
                        'repository' => $this->askRequestHeadersSchemaRepository($input, $output)
                    ]
                ],
                'parameters' => [
                    'schema' => [
                        'repository' => $this->askRequestParamsSchemaRepository($input, $output)
                    ]
                ]
            ],
            'response' => [
                'contentType' => $this->askResponseContentType($input, $output)
            ]
        ]);
    }

    private function askVersion(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getVersion()
        );

        $output->writeln($questionText);

        $question = new Question('Version: ', $this->defaultEndpointConfig->getVersion());
        return $helper->ask($input, $output, $question);
    }

    private function askUrlPrefix(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getUrlPrefix()
        );

        $output->writeln($questionText);

        $question = new Question('URL Prefix: ', $this->defaultEndpointConfig->getUrlPrefix());
        return $helper->ask($input, $output, $question);
    }

    private function askAuthenticationType(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getAuthenticationType()
        );

        $output->writeln($questionText);

        $question = new Question('Authentication type: ', $this->defaultEndpointConfig->getAuthenticationType());
        return $helper->ask($input, $output, $question);
    }

    private function askName(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getName()
        );

        $output->writeln($questionText);

        $question = new Question('Name: ', $this->defaultEndpointConfig->getName());
        return $helper->ask($input, $output, $question);
    }

    private function askDescription(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getDescription()
        );

        $output->writeln($questionText);

        $question = new Question('Description: ', $this->defaultEndpointConfig->getDescription());
        return $helper->ask($input, $output, $question);
    }

    private function askDispatcherClass(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getDispatcherClass()
        );

        $output->writeln($questionText);

        $question = new Question('Dispatcher class: ', $this->defaultEndpointConfig->getDispatcherClass());
        return $helper->ask($input, $output, $question);
    }

    private function askRequestMethod(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getRequestMethod()
        );

        $output->writeln($questionText);

        $question = new Question('Request method: ', $this->defaultEndpointConfig->getRequestMethod());
        return $helper->ask($input, $output, $question);
    }

    private function askRequestHeadersSchemaRepository(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getRequestHeadersSchemaRepository()
        );

        $output->writeln($questionText);

        $question = new Question(
            'Request headers schema repository: ',
            $this->defaultEndpointConfig->getRequestHeadersSchemaRepository()
        );

        return $helper->ask($input, $output, $question);
    }

    private function askRequestParamsSchemaRepository(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getRequestParamsSchemaRepository()
        );

        $output->writeln($questionText);

        $question = new Question(
            'Request parameters schema repository: ',
            $this->defaultEndpointConfig->getRequestParamsSchemaRepository()
        );

        return $helper->ask($input, $output, $question);
    }

    private function askResponseContentType(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $questionText = sprintf(
            "\n<info>Press enter to use default:</info> <fg=yellow>[%s]</>\n",
            $this->defaultEndpointConfig->getResponseContentType()
        );

        $output->writeln($questionText);

        $question = new Question('Response content-type: ', $this->defaultEndpointConfig->getResponseContentType());
        return $helper->ask($input, $output, $question);
    }
}