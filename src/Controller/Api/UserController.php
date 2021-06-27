<?php


namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(phat="/userin")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     **/
    public function getActions () {
        return $this->getUser()->getEmail();
    }
}