<?php

namespace Database\Seeders;

use App\Models\Directorship;
use App\Models\Unity;
use App\Models\User;
use App\Models\UserDirectorship;
use App\Models\UserUnities;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'web-access']);
        Permission::create(['name' => 'app-access']);

        $generalDirectorRole = Role::create(['name' => 'general-director']);
        $generalDirectorRole->givePermissionTo(Permission::all());

        $directorRole = Role::create(['name' => 'director']);
        $directorRole->givePermissionTo(['web-access']);

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo(['web-access']);

        $salesmanRole = Role::create(['name' => 'salesman']);
        $salesmanRole->givePermissionTo(['app-access']);

        $generalDirector = User::create([
            'name' => 'Edson A. do Nascimento',
            'email' => 'pele@magazineaziul.com.br',
            'password' => Hash::make('123mudar'),
        ]);

        $generalDirector->assignRole('general-director');

        $directorships = [
            'South' => 'Vagner Mancini',
            'Southeast' => 'Abel Ferreira',
            'Midwest' => 'Rogerio Ceni',
        ];

        foreach ($directorships as $directorshipName => $directorName) {
            $directorEmail = strtolower(str_replace(' ', '.', $directorName)) . '@magazineaziul.com.br';

            $director = User::create([
                'name' => $directorName,
                'email' => $directorEmail,
                'password' => Hash::make('123mudar'),
            ]);

            $director->assignRole('director');

            $directorship = Directorship::create([
                'name' => $directorshipName,
            ]);

            UserDirectorship::create([
                'user_id' => $director->id,
                'directorship_id' => $directorship->id,
            ]);
        }

        $units = [
            ['Porto Alegre', '-30.048750057541955', '-51.228587422990806', 'Ronaldinho Gaucho', 'South'],
            ['Florianopolis', '-27.55393525017396', '-48.49841515885026', 'Roberto Firmino', 'South'],
            ['Curitiba', '-25.473704465731746', '-49.24787198992874', 'Alex de Souza', 'South'],
            ['Sao Paulo', '-23.544259437612844', '-46.64370714029131', 'Françoaldo Souza', 'Southeast'],
            ['Rio de Janeiro', '-22.923447510604802', '-43.23208495438858', 'Romário Faria', 'Southeast'],
            ['Belo Horizonte', '-19.917854829716372', '-43.94089385954766', 'Ricardo Goulart', 'Southeast'],
            ['Vitória', '-20.340992420772206', '-40.38332271475097', 'Dejan Petkovic', 'Southeast'],
            ['Campo Grande', '-20.462652006300377', '-54.615658937666645', 'Deyverson Acosta', 'Midwest'],
            ['Goiania', '-16.673126240814387', '-49.25248826354209', 'Harlei Silva', 'Midwest'],
            ['Cuiaba', '-15.601754458320842', '-56.09832706558089', 'Walter Henrique', 'Midwest'],
        ];

        foreach ($units as $unit) {
            $directorship = Directorship::where('name', $unit[4])->first();
            $managerEmail = strtolower(str_replace(' ', '.', $unit[3])) . '@magazineaziul.com.br';

            $manager = User::create([
                'name' => $unit[3],
                'email' => $managerEmail,
                'password' => Hash::make('123mudar'),
            ]);

            $manager->assignRole('manager');

            $createdUnit = Unity::create([
                'name' => $unit[0],
                'lat' => $unit[1],
                'long' => $unit[2],
                'directorship_id' => $directorship->id,
            ]);

            UserUnities::create([
                'user_id' => $manager->id,
                'unity_id' => $createdUnit->id,
            ]);
        }

        $sellers = [
            ['Afonso Afancar', 'Belo Horizonte', 'afonso.afancar@magazineaziul.com.br'],
            ['Alceu Andreoli', 'Belo Horizonte', 'alceu.andreoli@magazineaziul.com.br'],
            ['Amalia Zago', 'Belo Horizonte', 'amalia.zago@magazineaziul.com.br'],
            ['Carlos Eduardo', 'Belo Horizonte', 'carlos.eduardo@magazineaziul.com.br'],
            ['Luiz Felipe', 'Belo Horizonte', 'luiz.felipe@magazineaziul.com.br'],
            ['Breno', 'Campo Grande', 'breno@magazineaziul.com.br'],
            ['Emanuel', 'Campo Grande', 'emanuel@magazineaziul.com.br'],
            ['Ryan', 'Campo Grande', 'ryan@magazineaziul.com.br'],
            ['Vitor Hugo', 'Campo Grande', 'vitor.hugo@magazineaziul.com.br'],
            ['Yuri', 'Campo Grande', 'yuri@magazineaziul.com.br'],
            ['Benjamin', 'Cuiaba', 'benjamin@magazineaziul.com.br'],
            ['Erick', 'Cuiaba', 'erick@magazineaziul.com.br'],
            ['Enzo Gabriel', 'Cuiaba', 'enzo.gabriel@magazineaziul.com.br'],
            ['Fernando', 'Cuiaba', 'fernando@magazineaziul.com.br'],
            ['Joaquim', 'Cuiaba', 'joaquim@magazineaziul.com.br'],
            ['André', 'Curitiba', 'andré@magazineaziul.com.br'],
            ['Raul', 'Curitiba', 'raul@magazineaziul.com.br'],
            ['Marcelo', 'Curitiba', 'marcelo@magazineaziul.com.br'],
            ['Julio César', 'Curitiba', 'julio.césar@magazineaziul.com.br'],
            ['Cauê', 'Curitiba', 'cauê@magazineaziul.com.br'],
            ['Benício', 'Florianopolis', 'benício@magazineaziul.com.br'],
            ['Vitor Gabriel', 'Florianopolis', 'vitor.gabriel@magazineaziul.com.br'],
            ['Augusto', 'Florianopolis', 'augusto@magazineaziul.com.br'],
            ['Pedro Lucas', 'Florianopolis', 'pedro.lucas@magazineaziul.com.br'],
            ['Luiz Gustavo', 'Florianopolis', 'luiz.gustavo@magazineaziul.com.br'],
            ['Giovanni', 'Goiania', 'giovanni@magazineaziul.com.br'],
            ['Renato', 'Goiania', 'renato@magazineaziul.com.br'],
            ['Diego', 'Goiania', 'diego@magazineaziul.com.br'],
            ['João Paulo', 'Goiania', 'joão.paulo@magazineaziul.com.br'],
            ['Renan', 'Goiania', 'renan@magazineaziul.com.br'],
            ['Luiz Fernando', 'Porto Alegre', 'luiz.fernando@magazineaziul.com.br'],
            ['Anthony', 'Porto Alegre', 'anthony@magazineaziul.com.br'],
            ['Lucas Gabriel', 'Porto Alegre', 'lucas.gabriel@magazineaziul.com.br'],
            ['Thales', 'Porto Alegre', 'thales@magazineaziul.com.br'],
            ['Luiz Miguel', 'Porto Alegre', 'luiz.miguel@magazineaziul.com.br'],
            ['Henry', 'Rio de Janeiro', 'henry@magazineaziul.com.br'],
            ['Marcos Vinicius', 'Rio de Janeiro', 'marcos.vinicius@magazineaziul.com.br'],
            ['Kevin', 'Rio de Janeiro', 'kevin@magazineaziul.com.br'],
            ['Levi', 'Rio de Janeiro', 'levi@magazineaziul.com.br'],
            ['Enrico', 'Rio de Janeiro', 'enrico@magazineaziul.com.br'],
            ['João Lucas', 'Sao Paulo', 'joão.lucas@magazineaziul.com.br'],
            ['Hugo', 'Sao Paulo', 'hugo@magazineaziul.com.br'],
            ['Luiz Guilherme', 'Sao Paulo', 'luiz.guilherme@magazineaziul.com.br'],
            ['Matheus Henrique', 'Sao Paulo', 'matheus.henrique@magazineaziul.com.br'],
            ['Miguel', 'Sao Paulo', 'miguel@magazineaziul.com.br'],
            ['Davi', 'Vitória', 'davi@magazineaziul.com.br'],
            ['Gabriel', 'Vitória', 'gabriel@magazineaziul.com.br'],
            ['Arthur', 'Vitória', 'arthur@magazineaziul.com.br'],
            ['Lucas', 'Vitória', 'lucas@magazineaziul.com.br'],
            ['Matheus', 'Vitória', 'matheus@magazineaziul.com.br'],
        ];

        foreach ($sellers as $seller) {
            $unit = Unity::where('name', $seller[1])->first();

            $seller = User::create([
                'name' => $seller[0],
                'email' => $seller[2],
                'password' => Hash::make('123mudar'),
            ]);

            $seller->assignRole('salesman');

            UserUnities::create([
                'user_id' => $seller->id,
                'unity_id' => $unit->id,
            ]);
        }
    }
}
