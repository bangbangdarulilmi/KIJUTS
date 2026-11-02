<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hmif:create-admin 
                            {--name=Super Admin HMIF : Nama lengkap administrator}
                            {--email=admin@sttcipasung.ac.id : Alamat email administrator}
                            {--nim=ADMIN001 : NIM / Identitas}
                            {--password=password : Kata sandi administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Super Admin / Koor Divisi Medinfo awal untuk Web Portal HMIF';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $nim = $this->option('nim');
        $password = $this->option('password');

        $user = User::where('email', $email)->orWhere('nim', $nim)->first();

        if ($user) {
            $user->update([
                'name' => $name,
                'email' => $email,
                'nim' => $nim,
                'role' => 'super_admin',
                'status' => 'active',
                'password' => Hash::make($password),
            ]);
            $this->info("Akun administrator yang sudah ada berhasil diperbarui menjadi Super Admin.");
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'nim' => $nim,
                'role' => 'super_admin',
                'status' => 'active',
                'password' => Hash::make($password),
            ]);

            Member::create([
                'user_id' => $user->id,
                'name' => $name,
                'nim' => $nim,
                'generation' => date('Y'),
                'status' => 'active',
                'role_in_org' => 'Super Admin / Medinfo',
            ]);

            $this->info("Akun Super Admin baru berhasil dibuat!");
        }

        $this->table(
            ['Parameter', 'Keterangan'],
            [
                ['Nama', $name],
                ['NIM', $nim],
                ['Email', $email],
                ['Password', $password],
                ['Role', 'super_admin'],
            ]
        );

        $this->comment("Anda sekarang dapat login ke portal di /login menggunakan NIM atau Email tersebut.");

        return Command::SUCCESS;
    }
}
