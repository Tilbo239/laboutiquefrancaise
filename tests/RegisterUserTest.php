<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        // Create a client to browse the application
        $client = static::createClient();
        $client->request('GET', '/inscription');

        // Generate a unique email to avoid conflicts with existing users
        $email = 'user' . uniqid() . '@example.com';

        // Fill the registration form
        $client->submitForm('S\'inscrire', [
            'register_user[firstname]' => 'Tilbo',
            'register_user[lastname]' => 'Roaim',
            'register_user[email]' => $email,
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456',
        ]);

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.")');
    }
}
