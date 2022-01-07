<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Employee;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EmployeesTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateEmployee(): void
    {
        $response = static::createClient()->request('POST', '/employees', ['json' => [
            'login' => 'kacper',
            'password' => 'Password123!_',
            'position' => 'developer',
            'phoneNumber' => '+48123123123',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/Employee',
            '@type' => 'Employee',
            'login' => 'kacper',
            'position' => 'developer',
            'phoneNumber' => '+48123123123',
        ]);
        $this->assertMatchesRegularExpression('~^/employees/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Employee::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateEmployeeWithInvalidLogin(): void
    {
        static::createClient()->request('POST', '/employees', ['json' => [
            'login' => 'kacp',
            'password' => 'Password123!_',
            'position' => 'developer',
            'phoneNumber' => '+48123123123',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'login: This value is too short. It should have 5 characters or more.',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateEmployeeWithInvalidPassword(): void
    {
        static::createClient()->request('POST', '/employees', ['json' => [
            'login' => 'kacper2',
            'password' => 'Password123',
            'position' => 'developer',
            'phoneNumber' => '+48123123123',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'password: Password should contain both lowercase and uppercase alpha characters, a digit and a special character.',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateEmployeeWithInvalidPhoneNumber(): void
    {
        static::createClient()->request('POST', '/employees', ['json' => [
            'login' => 'kacper2',
            'password' => 'Password123!_',
            'position' => 'developer',
            'phoneNumber' => '+49123123123',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'phoneNumber: This value should be in format +48XXXXXXXXX.',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testCreateEmployeeWithInvalidPosition(): void
    {
        static::createClient()->request('POST', '/employees', ['json' => [
            'login' => 'kacper2',
            'password' => 'Password123!_',
            'position' => 'devoper',
            'phoneNumber' => '+48123123123',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'position: This should be developer, hr, manager or tester.',
        ]);
    }
}
