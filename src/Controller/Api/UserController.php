<?php


namespace App\Controller\Api;

use App\Entity\User;
use App\Form\Model\UserDto;
use App\Form\Type\UserFormType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher){
        $this->em = $em;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @Rest\Get(phat="/userin")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     **/
    public function getActions () {
        return $this->getUser();
    }

    /**
     * @Rest\Post(phat="/singup")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     **/
    public function postActions (Request $request) {
        $userDto = new UserDto();
        $form = $this->createForm(UserFormType::class, $userDto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setName($userDto->name);
            $user->setEmail($userDto->email);
            $password = $this->userPasswordHasher->hashPassword(
                $user,
                $userDto->password
            );
            $user->setPassword($password);
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }
        return $form;
    }
}