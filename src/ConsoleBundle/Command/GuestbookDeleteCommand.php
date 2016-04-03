<?php

namespace ConsoleBundle\Command;

use Application\ValueObject\Order;
use Domain\Entity\Guestbook;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GuestbookDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('guestbook:delete')
            ->setDescription('Command guestbook:delete. Delete  N last records.')
            ->addArgument(
                'N',
                InputArgument::REQUIRED,
                'How many records you want to delete?'
            )
        ;
    }

    /**
     * Execute method of command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$rm = $this->getContainer()->get('infrastructure.repository_manager');
	$del_count = intval($input->getArgument('N'));
	$order = new Order();
	$order->add('Guestbook.created','DESC');
        $posts = $rm->getResult('Guestbook', null, $order, $del_count);

        $i = 0;
        foreach ($posts as $post) {
            $rm->remove($post);
            $i++;
        }
        $rm->flush();
        $output->write('Done. Number of deleted records: '.$i);
    }
}
