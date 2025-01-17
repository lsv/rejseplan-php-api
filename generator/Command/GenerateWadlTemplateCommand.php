<?php

namespace Lsv\Wasl\Generator\Command;

use Lsv\Wasl\Generator\Model\OptionModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Twig\Environment;
use Twig\Parser;

class GenerateWadlTemplateCommand extends Command
{
    public function __construct(
        private readonly Environment $twig,
    )
    {
        parent::__construct();
    }

    public function getName(): ?string
    {
        return 'WASL Generator';
    }

    protected function configure(): void
    {
        $this->addOption('src-directory', 's', InputOption::VALUE_REQUIRED, 'Directory to where generated file will be placed', __DIR__ . '/../generated');
        $this->addArgument('wadl-url', InputArgument::OPTIONAL, 'Url to the WADL file');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $directory = (string)$input->getOption('src-directory');

        if (!is_dir($directory)) {
            $output->writeln('The source file directory does not exists');
            $confirm = new ConfirmationQuestion('Should it be generated? ');
            if ($questionHelper->ask($input, $output, $confirm) && !mkdir($concurrentDirectory = $directory) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        if (!$input->getArgument('wadl-url')) {
            $question = new Question('URL to WADL? ');
            $input->setArgument('wadl-url', $questionHelper->ask($input, $output, $question));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //$wadl = file_get_contents($input->getArgument('wadl-url'));

        /** @var OptionModel[] $options */
        $options = [
            new OptionModel('foo', 'string', true),
            new OptionModel('bar', 'int', false),
        ];

        $template = $this->twig->load('class.php.twig');
        $content = $template->render([
            'namespace' => 'yy',
            'class_name' => 'xx',
            'options' => $options,
        ]);
        echo $content;

        return 1;
    }


}