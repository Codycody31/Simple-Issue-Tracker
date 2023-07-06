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
        // Create departments
        \App\Models\Department::factory()->createMany(
            [
                [
                    'name' => 'IT Department',
                    'description' => 'Information Technology Department',
                ],
                [
                    'name' => 'HR Department',
                    'description' => 'Human Resources Department',
                ],
                [
                    'name' => 'Finance Department',
                    'description' => 'Finance Department',
                ],
                [
                    'name' => 'Marketing',
                    'description' => 'Marketing Department',
                ]
            ]
        );

        // Create users
        \App\Models\User::factory()->createMany(
            [
                [
                    'fullname' => 'Administrator',
                    'email' => 'admin@sample.com',
                    'contact' => '09123456789',
                    'username' => 'admin',
                    'password' => bcrypt('0192023a7bbd73250516f069df18b500'),
                    'department_id' => 1,
                    'type' => 1,
                    'designation' => 'System Administrator',
                ],
                [
                    'fullname' => 'John Smith',
                    'email' => 'jsmith@sample.com',
                    'contact' => 'jsmith',
                    'username' => 'jsmith',
                    'password' => bcrypt('39ce7e2a8573b41ce73b5ba41617f8f7'),
                    'department_id' => 1,
                    'type' => 2,
                    'designation' => 'Programmer',
                ],
                [
                    'fullname' => 'Claire Blake',
                    'email' => 'cblake@gmail.com',
                    'contact' => '09123456789',
                    'username' => 'cblake',
                    'password' => bcrypt('cd74fae0a3adf459f73bbf187607ccea'),
                    'department_id' => 2,
                    'type' => 2,
                    'designation' => 'Mktg. Manager',
                ]
            ]
        );

        // Create issues
        \App\Models\Issue::factory()->createMany(
            [
                [
                    'title' => 'Sample Issue',
                    'description' => 'This is only a sample issue.',
                    'department_id' => 2,
                    'user_id' => 2,
                    'status' => 1,
                ],
                [
                    'title' => 'Issue 102',
                    'description' => 'This is a sample issue only 102.

                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris ullamcorper tellus eu condimentum accumsan. Ut tempus rutrum tortor eget hendrerit. Quisque consectetur risus ac leo ullamcorper efficitur. Praesent aliquet sem commodo risus pharetra, quis convallis lorem tincidunt. Praesent scelerisque mollis magna.',
                    'department_id' => 2,
                    'user_id' => 2,
                    'status' => 0,
                ]
            ]
        );

        // Create issue comments
        \App\Models\Comment::factory()->createMany(
            [
                [
                    'issue_id' => 1,
                    'comment' => 'Sample Comment 101',
                    'user_id' => 2,
                ],
                [
                    'issue_id' => 1,
                    'comment' => 'We will fix this issue',
                    'user_id' => 3,
                ],
                [
                    'issue_id' => 2,
                    'comment' => 'Sample Comment',
                    'user_id' => 2,
                ]
            ]
        );
    }
}
