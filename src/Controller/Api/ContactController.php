<?php


namespace App\Controller\Api;

use App\Entity\Contact;
use App\Form\Model\ContactDto;
use App\Form\Type\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @Rest\Post(phat="/contact")
     * @Rest\View(serializerGroups={"contact"}, serializerEnableMaxDepthChecks=true)
     **/
    public function postActions (Request $request) {
        $contactDto = new ContactDto();
        $form = $this->createForm(ContactFormType::class, $contactDto);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $datetime = new \DateTime('@'.strtotime('now'));
            $contact = new Contact();
            $contact->setName($contactDto->name);
            $contact->setEmail($contactDto->email);
            $contact->setMessage($contactDto->message);
            $contact->setDate($datetime);
            $this->em->persist($contact);
            $this->em->flush();
            return $contact;
        }
        return $form;
    }

}