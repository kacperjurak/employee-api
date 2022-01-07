<?php

namespace App\MessageHandler;

use App\Entity\Employee;
use App\Message\ReqresNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ReqresNotificationHandler implements MessageHandlerInterface
{
    public function __construct(private HttpClientInterface $client, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function __invoke(ReqresNotification $reqresNotification)
    {
        $response = $this->client->request(
            'POST',
            'https://reqres.in/api/users',
            [
                'json' => [
                    'name' => $reqresNotification->getLogin(),
                    'job' => $reqresNotification->getPosition()],
            ],
        );

        /** @var Employee $employee */
        $employee = $this->entityManager->getRepository(Employee::class)->find($reqresNotification->getId());

        $content = $response->toArray();

        if ($content['id'] !== null) {
            $employee->setExternalId($content['id']);
            $this->entityManager->persist($employee);
            $this->entityManager->flush();
        }
    }
}
