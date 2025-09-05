<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user')]
class CreateUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $em, private UserPasswordHasherInterface $hasher){ parent::__construct(); }

    protected function configure(){ $this->addArgument('email', InputArgument::REQUIRED)->addArgument('password', InputArgument::REQUIRED); }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email'); $password = $input->getArgument('password');
        $user = new User(); $user->setEmail($email); $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->em->persist($user); $this->em->flush();
        $output->writeln('User created: '.$email);
        return Command::SUCCESS;
    }
}
