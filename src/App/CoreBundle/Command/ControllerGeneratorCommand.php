<?php
namespace App\CoreBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;


class ControllerGeneratorCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('mywebsite:generate-controller')
            ->setDefinition(array(
                new InputOption('controller', '', InputOption::VALUE_REQUIRED, 'Le nom du contrôler a creer'),
                new InputOption('bundle', '', InputOption::VALUE_REQUIRED, 'Le bundle dans lequel créer le contrôleur'),
                new InputOption('basecontroller', '', InputOption::VALUE_REQUIRED, 'S\'il faut ou non heriter du controlleur de base de Symfony2')
            ))
            ->setDescription('Genere le code de base pour commencer a utiliser un contrôleur')
            ->setHelp('Cette commande vous permet de facilement generer le code necessaire pour commencer a travailler avec un controlleur.');

    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // On affiche quelques infos
        $dialog = $this->getQuestionHelper();
        $output->writeln(array(
            '',
            '      Bienvenue dans le générateur de controleurs',
            '',
            'Cet outil va vous permettre de générer rapidement votre contrôleur',
            '',
        ));

        // On récupère les informations de l'utilisateur
        $controller = $dialog->ask(
            $input,
            $output,
            new Question('Nom du controleur: ', $input->getOption('controller'))
        );

        $basecontroller = $input->getOption('basecontroller');
        if (!$basecontroller && !$dialog->ask($input, $output, new Question('Voulez vous que le bundle étende le controlleur de base de Symfony2 ?', 'yes', '?'))) {
            $basecontroller = false;
        }

        $bundleName = $dialog->ask(
            $input,
            $output,
            new Question ('bundle: ', $input->getOption('bundle'))
        );

        // On sauvegarde les paramètres
        $input->setOption('controller', $controller);
        $input->setOption('basecontroller', $basecontroller);
        $input->setOption('bundle', $bundleName);
    }

    protected function getQuestionHelper()
    {
        $dialog = $this->getHelper('question');
        if (!$dialog || get_class($dialog) !== 'Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper') {
            $this->getHelperSet()->set($dialog = new QuestionHelper());
        }

        return $dialog;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getQuestionHelper();

        if ($input->isInteractive()) {
            if (!$dialog->ask($input, $output, new Question('Do you confirm generation? ', 'yes', '?'))) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }
        // On recupere les options
        $controller = $input->getOption('controller');
        $basecontroller = $input->getOption('basecontroller');
        $bundleName = $input->getOption('bundle');

        // On recupere les infos sur le bundle nécessaire à la génération du controller
        $kernel = $this->getContainer()->get('kernel');
        $bundle = $kernel->getBundle($bundleName);
        $namespace = $bundle->getNamespace();
        $path = $bundle->getPath();
        $target = $path . '/Controller/' . $controller . 'Controller.php';

        // On génère le contenu du controleur
        $twig = $this->getContainer()->get('templating');

        $controller_code = $twig->render('controllerCommand/controller.php.twig',
            array(
                'controller' => $controller,
                'basecontroller' => $basecontroller,
                'namespace' => $namespace
            )
        );

        // On crée le fichier
        if (!is_dir(dirname($target))) {
            mkdir(dirname($target), 0777, true);
        }
        file_put_contents($target, $controller_code);

        $logger = $this->getContainer()->get('logger');

        $logger->debug('This is a debug message');
        $logger->info('This is an info message');
        $logger->notice('This is an notice message');
        $logger->warning('This is a warning message');
        $logger->error('This is an error message');
        $logger->critical('This is a critical message');
        $logger->alert('This is an alert message');
        $logger->emergency('This is an emergency message');

        return 0;
    }
}