<?php

namespace Database\Factories;

use App\Entities\Users\User;
use App\Enums\Users\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf_cnpj' => $this->generateCpfOrCnpj(),
            'user_type' => $this->generateUserType()
        ];
    }

    public function newModel(array $attributes = [])
    {
        return $this->model::fromArray($attributes);
    }

    private function generateCpfOrCnpj()
    {
        return !($this->faker->numberBetween() % 2)
            ? $this->faker->cpf()
            : $this->faker->cnpj();
    }

    private function generateUserType()
    {
        return ($this->faker->numberBetween(1, 2) === 1)
            ? UserType::COMMON
            : UserType::SHOPKEEPER;
    }
}
