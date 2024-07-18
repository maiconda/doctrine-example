<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events', name: 'event_')]
class EventController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }
    #[Route('/store', name: 'store', methods: ['GET'])]
    public function store(EntityManagerInterface $manager): Response
    {
        $timezone = new DateTimeZone('America/Sao_Paulo');
        $event = new Event();
        $event->setTitle('Title test');
        $event->setBody('Body test');
        $event->setSlug('Slug test');
        $event->setStartDate(new \DateTime('2024-10-30 14:00:00', $timezone));
        $event->setEndDate(new \DateTime('2024-10-30 14:00:00', $timezone));
        $event->setCreatedAt(new \DateTimeImmutable('now', $timezone));
        $event->setUpdatedAt(new \DateTimeImmutable('now', $timezone));

        $manager->persist($event);
        $manager->flush();

        return new Response('Event stored');
    }

    #[Route('/update', name: 'update', methods: ['GET'])]
    public function update(EntityManagerInterface $manager, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find(1);

        if(!$event) throw $this->createNotFoundException('Event not found');

        $event->setTitle('Title test 2');
        $event->setBody('Body test 2');
        $event->setSlug('Slug test 2');

        $manager->flush();

        return new Response('Event updated');
    }

    #[Route('/remove', name: 'remove', methods: ['GET'])]
    public function remove(EntityManagerInterface $manager, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find(1);

        if(!$event) throw $this->createNotFoundException('Event not found');

        $manager->remove($event);
        $manager->flush();

        return new Response('Event removed');
    }
}
