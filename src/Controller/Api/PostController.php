<?php


namespace App\Controller\Api;

use App\Entity\Post;
use App\Form\Model\PostDto;
use App\Form\Type\PostFormType;
use App\Repository\BlogRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractFOSRestController
{
    /**
     * @var FileUploader
     */
    private $fileUploader;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, FileUploader $fileUploader){
        $this->em = $em;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Rest\Get(phat="/posts")
     * @Rest\View(serializerGroups={"post"}, serializerEnableMaxDepthChecks=true)
     **/
    public function getActions (Request $request) {
        return $this->em->getRepository('App:Post')->findAll();
    }

    /**
     * @Rest\Get(phat="/posts/{id}")
     * @Rest\View(serializerGroups={"post"}, serializerEnableMaxDepthChecks=true)
     **/
    public function getPostId (Request $request) {
        return $this->em->getRepository('App:Post')->find($request->get('id'));
    }

    /**
     * @Rest\Post(phat="/posts")
     * @Rest\View(serializerGroups={"post"}, serializerEnableMaxDepthChecks=true)
     **/
    public function postActions(Request $request) {
        $postDto = new PostDto();
        $form = $this->createForm(PostFormType::class, $postDto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $datetime = new \DateTime('@'.strtotime('now'));
            $fileName = $postDto->base64Image ? $this->fileUploader->uploadBase64File($postDto->base64Image) : null;
            $post = new Post();
            $post->setTitle($postDto->title);
            $post->setText($postDto->text);
            $post->setDate($datetime);
            $post->setImage($fileName);
            $post->setBlog($this->em->getRepository('App:Blog')->find($request->get('blog_id')));
            $post->setUser($this->getUser());
            $this->em->persist($post);
            $this->em->flush();
            return $post;
        }
        return $form;
    }

    /**
     * @Rest\Put(phat="/posts/{id}")
     * @Rest\View(serializerGroups={"post"}, serializerEnableMaxDepthChecks=true)
     **/
    public function putActions($id, Request $request) {
        $content = json_decode($request->getContent(), true);
        $postDto = new PostDto();
        $form = $this->createForm(PostFormType::class, $postDto);
        $form->submit($content);
        if($form->isSubmitted() && $form->isValid()) {
            $datetime = new \DateTime('@'.strtotime('now'));
            $fileName = $postDto->base64Image ? $this->fileUploader->uploadBase64File($postDto->base64Image) : null;
            $post = $this->em->getRepository('App:Post')->find($id);
            $post->setTitle($postDto->title);
            $post->setText($postDto->text);
            $post->setDate($datetime);
            $post->setImage($fileName);
            $post->setBlog($this->em->getRepository('App:Blog')->find($request->get('blog_id')));
            $post->setUser($this->getUser());
            $this->em->persist($post);
            $this->em->flush();
            return $post;
        }
        return $form;
    }

}