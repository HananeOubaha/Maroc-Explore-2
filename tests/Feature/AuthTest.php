<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['user', 'token']);
    }

    /** @test */
    public function a_user_can_login()
    {
        // Création de l'utilisateur
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Envoi de la requête de connexion
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Vérification de la structure de la réponse
        $response->assertStatus(200);

        // Vérifier si la réponse contient un token et user
        $response->assertJsonStructure([
            'token',  // Le token d'authentification
        ]);
        
        // Optionnel : vérifier que la réponse contient bien les données de l'utilisateur
        $responseData = $response->json();
        $this->assertArrayHasKey('user', $responseData);  // Assure que 'user' est bien présent
    }
}
