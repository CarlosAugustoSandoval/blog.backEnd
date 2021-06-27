<?php


namespace App\Controller\Api;

use App\Entity\Blog;
use App\Form\Model\BlogDto;
use App\Form\Type\BlogFormType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractFOSRestController
{
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em){
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @Rest\Get(phat="/blogs/{id}")
     * @Rest\View(serializerGroups={"blog"}, serializerEnableMaxDepthChecks=true)
     **/
    public function getActions (Request $request, BlogRepository $blogRepository) {
        return $blogRepository->find($request->get('id'));
//        return $blogRepository->findOneBy(['user' => $this->getUser()]);
    }

    /**
     * @Rest\Post(phat="/blogs")
     * @Rest\View(serializerGroups={"blog"}, serializerEnableMaxDepthChecks=true)
     **/
    public function postActions (Request $request) {
        $blogDto = new BlogDto();
        $form = $this->createForm(BlogFormType::class, $blogDto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $blog = new Blog();
            $blog->setTitle($blogDto->title);
            $blog->setText($blogDto->text);
            $blog->setUser($this->getUser());
            $this->em->persist($blog);
            $this->em->flush();
            return $blog;
        }
        return $form;
    }

}