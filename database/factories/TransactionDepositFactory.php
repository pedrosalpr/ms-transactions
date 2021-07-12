<?php

namespace Database\Factories;

use App\Entities\Transactions\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionDepositFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transaction_id' => $this->faker->uuid(),
            'user_id' => $this->faker->randomDigitNotZero(),
            'value' => $this->faker->randomFloat(2),
            'type' => TransactionType::deposit()->getValue(),
            'time' => Carbon::now(),
            'description' => $this->faker->paragraph(1)
        ];
    }
}
