<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'User1',
            'email' => 'test1@example.com',
            'avatar' =>'https://i.ex-cdn.com/mgn.vn/files/content/2022/12/07/anh1-1805.jpg',
            'password' => bcrypt('password'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User2',
            'email' => 'test2@example.com',
            'avatar' =>'https://img4.thuthuatphanmem.vn/uploads/2020/05/14/roronoa-zoro_092218456.jpg',
            'password' => bcrypt('password'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User3',
            'email' => 'test3@example.com',
            'avatar' =>'https://wanderound.com.vn/wp-content/uploads/2022/08/Bang-xep-hang-danh-tieng-thanh-vien-bang-mu-rom-2.jpg',
            'password' => bcrypt('password'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Quốc Kỳ',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // conversation

        \App\Models\Conversation::factory()->create(([
            'user1' => 1,
            'user2' => 2,
        ]));
        \App\Models\Conversation::factory()->create(([
            'user1' => 1,
            'user2' => 3,
        ]));
        \App\Models\Conversation::factory()->create(([
            'user1' => 3,
            'user2' => 2,
        ]));
    }
}
