<?php

require __DIR__ . '/../../bootstrap.php';

use App\Models\User;
use App\Models\Address;
use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;

$faker = Factory::create();

$numberOfUsers = 10;
$addressesPerUser = 3;

echo "Insertion des utilisateurs...\n";

try {
    DB::beginTransaction();

    for ($i = 0; $i < $numberOfUsers; $i++) {
        $user = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => password_hash('password', PASSWORD_DEFAULT),
        ]);

        echo "Utilisateur créé : {$user->first_name} {$user->last_name} (ID: {$user->id})\n";

        for ($j = 0; $j < $addressesPerUser; $j++) {
            Address::create([
                'user_id' => $user->id,
                'street' => $faker->streetAddress,
                'city' => $faker->city,
                'country' => $faker->country,
                'postal_code' => $faker->postcode,
            ]);
        }
    }

    DB::commit();
    echo "Insertion terminée avec succès.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "Erreur lors de l'insertion : " . $e->getMessage() . "\n";
}
