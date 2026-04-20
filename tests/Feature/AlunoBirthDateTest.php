<?php

namespace Tests\Feature;

use App\Models\Aluno;
use App\Models\AcademiaUnidade;
use App\Models\AlunoPlanoUnidade;
use App\Models\FinancialTransaction;
use App\Models\Planos;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlunoBirthDateTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_creation_uses_birth_date_instead_of_age(): void
    {
        Permission::findOrCreate('students.manage', 'web');
        Role::findOrCreate('aluno', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');

        $response = $this->actingAs($manager)
            ->post(route('aluno.store'), [
                'name' => 'Aluno Teste',
                'email' => 'aluno@example.com',
                'cpf' => '12345678901',
                'telefone' => '11999999999',
                'sexo' => 'Masculino',
                'data_nascimento' => '2000-05-10',
            ]);

        $response->assertRedirect(route('aluno.index'));

        $aluno = Aluno::where('cpf', '12345678901')->firstOrFail();

        $this->assertSame('2000-05-10', $aluno->data_nascimento->toDateString());
    }

    public function test_student_creation_accepts_masked_fields_and_uploads_photo(): void
    {
        Storage::fake('public');

        Permission::findOrCreate('students.manage', 'web');
        Role::findOrCreate('aluno', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');

        $response = $this->actingAs($manager)
            ->post(route('aluno.store'), [
                'name' => 'Aluno Foto',
                'email' => 'aluno-foto@example.com',
                'cpf' => '123.456.789-01',
                'telefone' => '(11) 99999-8888',
                'sexo' => 'Feminino',
                'data_nascimento' => '1998-09-12',
                'foto' => UploadedFile::fake()->image('perfil.jpg'),
            ]);

        $response->assertRedirect(route('aluno.index'));

        $aluno = Aluno::whereHas('user', fn ($query) => $query->where('email', 'aluno-foto@example.com'))
            ->firstOrFail();

        $this->assertSame('12345678901', $aluno->cpf);
        $this->assertSame('11999998888', $aluno->telefone);
        $this->assertNotNull($aluno->foto);
        Storage::disk('public')->assertExists($aluno->foto);
        $this->assertSame(route('aluno.photo', ['path' => $aluno->foto]), $aluno->foto_url);
    }

    public function test_student_creation_sends_welcome_email(): void
    {
        Mail::fake();

        Permission::findOrCreate('students.manage', 'web');
        Role::findOrCreate('aluno', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');

        $response = $this->actingAs($manager)
            ->post(route('aluno.store'), [
                'name' => 'Aluno Email',
                'email' => 'aluno-email@example.com',
                'cpf' => '55566677788',
                'telefone' => '11999997777',
                'sexo' => 'Feminino',
                'data_nascimento' => '1997-07-20',
            ]);

        $response->assertRedirect(route('aluno.index'));

        Mail::assertSent(\App\Mail\StudentWelcomeMail::class, function ($mail) {
            return $mail->hasTo('aluno-email@example.com')
                && $mail->studentName === 'Aluno Email'
                && $mail->studentEmail === 'aluno-email@example.com';
        });
    }

    public function test_student_update_accepts_masked_fields_and_replaces_photo(): void
    {
        Storage::fake('public');

        Permission::findOrCreate('students.manage', 'web');
        $role = Role::findOrCreate('aluno', 'web');
        $unidade = AcademiaUnidade::create([
            'nome' => 'Unidade Atual',
            'endereco' => 'Rua Atual, 123',
        ]);

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');

        $student = User::factory()->create([
            'password' => Hash::make('secret'),
            'status' => true,
        ]);
        $student->assignRole($role);

        $oldPhoto = UploadedFile::fake()->image('old.jpg')->store('alunos', 'public');

        $student->aluno()->create([
            'registered_at' => now()->toDateString(),
            'cpf' => '11122233344',
            'telefone' => '11911112222',
            'sexo' => 'Masculino',
            'data_nascimento' => '2001-01-01',
            'unidade_id' => $unidade->id,
            'foto' => $oldPhoto,
        ]);

        $response = $this->actingAs($manager)
            ->put(route('aluno.update', $student->id), [
                'name' => 'Aluno Atualizado',
                'email' => $student->email,
                'cpf' => '111.222.333-44',
                'telefone' => '(11) 98888-7777',
                'sexo' => 'Masculino',
                'data_nascimento' => '2001-01-01',
                'foto' => UploadedFile::fake()->image('new.jpg'),
            ]);

        $response->assertRedirect(route('aluno.index'));

        $student->refresh();
        $student->load('aluno');

        $this->assertSame('11122233344', $student->aluno->cpf);
        $this->assertSame('11988887777', $student->aluno->telefone);
        $this->assertTrue((bool) $student->status);
        $this->assertSame($unidade->id, $student->aluno->unidade_id);
        $this->assertNotSame($oldPhoto, $student->aluno->foto);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($student->aluno->foto);
    }

    public function test_student_show_lists_only_plans_attached_to_student(): void
    {
        Role::findOrCreate('aluno', 'web');

        $viewer = User::factory()->create();

        $student = User::factory()->create([
            'status' => false,
        ]);
        $student->assignRole('aluno');
        $student->aluno()->create([
            'registered_at' => now()->toDateString(),
            'cpf' => '99988877766',
            'telefone' => '11999998888',
            'sexo' => 'Masculino',
            'data_nascimento' => '2000-01-01',
        ]);

        $attachedPlan = Planos::create([
            'name' => 'Plano Vinculado',
            'preco' => 99.90,
            'color' => '#000000',
        ]);

        $otherPlan = Planos::create([
            'name' => 'Plano Nao Vinculado',
            'preco' => 149.90,
            'color' => '#ffffff',
        ]);

        $unidade = AcademiaUnidade::create([
            'nome' => 'Unidade Centro',
            'endereco' => 'Rua Teste, 123',
        ]);

        $student->planos()->attach($attachedPlan->id, [
            'academia_unidade_id' => $unidade->id,
            'valor_inicial' => 99.90,
            'valor_total' => 99.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
        ]);

        $response = $this->actingAs($viewer)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Plano Vinculado');
        $response->assertDontSee('Plano Nao Vinculado');
        $response->assertSee('Fazer Avaliação Física');
        $response->assertSee('Ver Histórico de Avaliações');
        $response->assertSee(route('avaliacao.show', $student->id), false);
        $response->assertSee(route('planos.carrinho', ['user_id' => $student->id]), false);
    }

    public function test_plan_cart_preselects_student_from_query_string(): void
    {
        Role::findOrCreate('aluno', 'web');

        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'Aluno Carrinho',
        ]);
        $student->assignRole('aluno');

        $response = $this->actingAs($viewer)
            ->get(route('planos.carrinho', ['user_id' => $student->id]));

        $response->assertOk();
        $response->assertSee('value="' . $student->id . '" selected', false);
    }

    public function test_student_photo_route_serves_uploaded_file_from_storage(): void
    {
        Storage::fake('public');

        Role::findOrCreate('aluno', 'web');

        $viewer = User::factory()->create();
        $student = User::factory()->create();
        $student->assignRole('aluno');

        $photoPath = UploadedFile::fake()->image('perfil.jpg')->store('alunos', 'public');

        $student->aluno()->create([
            'registered_at' => now()->toDateString(),
            'cpf' => '12312312399',
            'telefone' => '11911110000',
            'sexo' => 'Feminino',
            'data_nascimento' => '2002-02-02',
            'foto' => $photoPath,
        ]);

        $response = $this->actingAs($viewer)
            ->get(route('aluno.photo', ['path' => $photoPath]));

        $response->assertOk();
        $response->assertHeader('content-type', 'image/jpeg');
    }

    public function test_disabling_student_clears_plan_contracts_and_marks_student_inactive(): void
    {
        Permission::findOrCreate('students.manage', 'web');
        $role = Role::findOrCreate('aluno', 'web');

        $manager = User::factory()->create();
        $manager->givePermissionTo('students.manage');

        $student = User::factory()->create([
            'status' => true,
        ]);
        $student->assignRole($role);

        $unit = AcademiaUnidade::create([
            'nome' => 'Unidade Cancelamento',
            'endereco' => 'Rua Cancelamento, 1',
        ]);

        $firstPlan = Planos::create([
            'name' => 'Plano Cancelado 1',
            'preco' => 99.90,
            'color' => '#101010',
        ]);

        $secondPlan = Planos::create([
            'name' => 'Plano Cancelado 2',
            'preco' => 149.90,
            'color' => '#202020',
        ]);

        $student->aluno()->create([
            'registered_at' => now()->toDateString(),
            'cpf' => '55544433322',
            'telefone' => '11977776666',
            'sexo' => 'Masculino',
            'data_nascimento' => '2000-01-01',
        ]);

        $firstContract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $firstPlan->id,
            'valor_inicial' => 99.90,
            'valor_total' => 99.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'pix',
            'periodicidade' => 'mensal',
            'data_vencimento' => now()->toDateString(),
        ]);

        $secondContract = AlunoPlanoUnidade::create([
            'user_id' => $student->id,
            'academia_unidade_id' => $unit->id,
            'plano_id' => $secondPlan->id,
            'valor_inicial' => 149.90,
            'valor_total' => 149.90,
            'valor_desconto' => 0,
            'forma_pagamento' => 'cartao',
            'periodicidade' => 'anual',
            'data_vencimento' => now()->toDateString(),
        ]);

        $firstPaidTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $firstContract->id,
            'description' => 'Mensalidade - Plano Cancelado 1',
            'due_date' => now()->toDateString(),
            'amount' => 99.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'pix',
            'amount_paid' => 99.90,
            'paid_at' => now()->toDateString(),
            'status' => 'pago',
        ]);

        $firstOpenTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $firstContract->id,
            'description' => 'Mensalidade - Plano Cancelado 1',
            'due_date' => now()->addMonth()->toDateString(),
            'amount' => 99.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'pix',
            'status' => 'pendente',
        ]);

        $secondPaidTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $secondContract->id,
            'description' => 'Anuidade - Plano Cancelado 2',
            'due_date' => now()->toDateString(),
            'amount' => 149.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'cartao',
            'amount_paid' => 149.90,
            'paid_at' => now()->toDateString(),
            'status' => 'pago',
        ]);

        $secondOpenTransaction = FinancialTransaction::create([
            'kind' => 'conta_receber',
            'academia_unidade_id' => $unit->id,
            'user_id' => $student->id,
            'aluno_plano_unidade_id' => $secondContract->id,
            'description' => 'Anuidade - Plano Cancelado 2',
            'due_date' => now()->addYear()->toDateString(),
            'amount' => 149.90,
            'discount' => 0,
            'addition' => 0,
            'payment_method' => 'cartao',
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($manager)
            ->post(route('aluno.toggleStatus', $student->id));

        $response->assertRedirect(route('aluno.index'));

        $student->refresh();

        $this->assertFalse($student->status);
        $this->assertDatabaseMissing('aluno_plano_unidade', ['id' => $firstContract->id]);
        $this->assertDatabaseMissing('aluno_plano_unidade', ['id' => $secondContract->id]);
        $this->assertSame('pago', $firstPaidTransaction->fresh()->status);
        $this->assertSame('cancelado', $firstOpenTransaction->fresh()->status);
        $this->assertSame('pago', $secondPaidTransaction->fresh()->status);
        $this->assertSame('cancelado', $secondOpenTransaction->fresh()->status);
        $this->assertDatabaseCount('financial_transactions', 4);
    }
}
