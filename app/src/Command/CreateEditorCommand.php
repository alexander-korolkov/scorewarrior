<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateEditorCommand extends Command
{
    protected static $defaultName = 'app:create-editor';
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new editor.')
            ->setHelp('This command allows you to create an editor...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $usernameQuestion = new Question('Please enter the username of the editor: ');
        $emailQuestion = new Question('Please enter the email of the editor: ');
        $passwordQuestion = new Question('Please enter the password of the editor: ');

        $username = $helper->ask($input, $output, $usernameQuestion);
        $email = $helper->ask($input, $output, $emailQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $editor = new User();
        $editor->setUsername($username);
        $editor->setEmail($email);
        $editor->setRoles(['ROLE_EDITOR']); // Set the role to editor
        // Use the new password hasher to encode the password
        $hashedPassword = $this->passwordHasher->hashPassword($editor, $password);
        $editor->setPassword($hashedPassword);

        $this->entityManager->persist($editor);
        $this->entityManager->flush();

        $output->writeln('Editor created successfully');

        return Command::SUCCESS;
    }
}
