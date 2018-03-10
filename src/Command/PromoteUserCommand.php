<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use App\Manager\UserManager;

class PromoteUserCommand extends Command
{
    /** @var UserManager **/
    protected $userManager;
    
    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setName('app:promote-user')
            ->setDescription('Set a user role')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'The role to give (lead || admin)')
            ->setHelp("This command allows you to set a role for a user...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $user = $this->userManager->promoteUser($input->getArgument('username'), $input->getArgument('role'));
            
            $output->writeln("<info>{$user->getUsername()} was promoted to role {$user->getRoles()[0]}</info>");
        } catch (UsernameNotFoundException $ex) {
            $output->writeln('<error>The given username is not associated to a registered account</error>');
        } catch (\InvalidArgumentException $ex) {
            $output->writeln('<error>The given role is invalid. Use the --help flag to see available values</error>');
        }
    }
}