<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Branch;
use App\Models\Device;
use App\Models\Category;
use App\Models\Product;
use App\Models\Session;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create SaaS Plans
        $plans = [
             Plan::factory()->create(['name' => 'Starter', 'price' => 29.99, 'device_limit' => 5]),
             Plan::factory()->create(['name' => 'Pro', 'price' => 59.99, 'device_limit' => 15]),
             Plan::factory()->create(['name' => 'Enterprise', 'price' => 99.99, 'device_limit' => 100]),
        ];

        // 2. Create Tenancy Base (Main Client)
        $tenant = Tenant::factory()->create([
            'name' => 'Antigravity Gaming Lounge',
            'plan_id' => $plans[1]->id, // Pro Plan
        ]);

        // 3. Create Admin User for this tenant
        User::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Super Admin',
            'email' => 'admin@playstation.saas',
            'password' => bcrypt('password'),
        ]);

        // 4. Create Branches
        $branches = Branch::factory(2)->create([
            'tenant_id' => $tenant->id,
        ]);

        // 5. Create Devices for each branch
        foreach ($branches as $branch) {
            Device::factory(8)->create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch->id,
            ]);
        }

        // 6. Create Cafeteria Structure
        $categories = Category::factory(4)->create([
            'tenant_id' => $tenant->id,
        ]);

        foreach ($categories as $category) {
            Product::factory(5)->create([
                'tenant_id' => $tenant->id,
                'category_id' => $category->id,
            ]);
        }

        // 7. Generate Historic Data (Revenue & Activity)
        Session::factory(50)->create([
            'tenant_id' => $tenant->id,
            'device_id' => Device::where('tenant_id', $tenant->id)->get()->random()->id,
            'user_id' => User::where('tenant_id', $tenant->id)->first()->id,
        ]);

        // 8. Generate Operational Expenses
        Expense::factory(20)->create([
            'tenant_id' => $tenant->id,
        ]);

        // 9. Generate POS Orders
        $products = Product::where('tenant_id', $tenant->id)->get();
        Order::factory(30)->create([
            'tenant_id' => $tenant->id,
            'user_id' => User::where('tenant_id', $tenant->id)->first()->id,
        ])->each(function ($order) use ($products) {
            $randomProducts = $products->random(rand(1, 3));
            foreach ($randomProducts as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 4),
                    'unit_price' => $product->price,
                    'total_price' => $product->price * rand(1, 4),
                ]);
            }
        });
    }
}
