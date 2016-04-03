<?php

namespace Application\UseCase\User;

use Application\Contract\Command as CommandInterface;
use Application\Contract\User as UserInterface;
use Application\Exception\BadRequestException;
use Application\UseCase\InsertUseCase as AbstractInsertUseCase;
use Domain\Adapter\ArrayCollection;
use Application\ValueObject\Criteria;
use Domain\Entity\User;
use Domain\Entity\Guestbook;

class InsertUseCase extends AbstractInsertUseCase
{
    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        $data = $_command->getData();
		
		$criteria = new Criteria(
            array(
                'comparison' => 'OR',
                'filters' =>
                    array(
                        array(
                            'type'       => 'string',
                            'field'      => 'User.username',
                            'value'      => $data->get('username'),
                            'comparison' => 'eq'
                        ),
						array(
                            'type'       => 'string',
                            'field'      => 'User.email',
                            'value'      => $data->get('email'),
                            'comparison' => 'eq'
                        )
                    )
            )
        );

        $exists = $this->rm->findOneBy('User', $criteria);
		if ($exists) throw new BadRequestException('User already exists');
 
        $user = new User(
            $data->get('username'),
            $data->get('password'),
            $data->get('email') 
        );

        $this->rm->persist($user);
      
		
		 $guestbook = new Guestbook(
            $data->get('username'),
            $data->get('email'),
            'Welcome new user: '. $data->get('username')
        );

        $this->rm->persist($guestbook);
		
		$this->rm->flush();

        return  'New user was added';
    }
}
