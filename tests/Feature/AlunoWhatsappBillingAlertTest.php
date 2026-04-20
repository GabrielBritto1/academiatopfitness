<?php

namespace Tests\Feature;

use App\Mail\BillingAlertEmail;
use App\Models\Aluno;
use App\Models\FinancialTransaction;
use App\Models\Role;
use App\Models\User;
use App\Models\WhatsappInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AlunoWhatsappBillingAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_show_displays_whatsapp_billing_tab_for_upcoming_charge(): void
    {
        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'João',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '11999998888',
            'sexo' => 'M',
            'data_nascimento' => '2000-01-01',
        ]);

        WhatsappInstance::create([
            'name' => 'Recepção',
            'base_url' => 'https://evolution.example.com',
            'instance_name' => 'recepcao',
            'api_key' => 'secret-key',
            'is_active' => true,
            'is_default' => true,
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade Abril',
            'due_date' => now()->addDays(2)->toDateString(),
            'amount' => 150,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($viewer)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Aviso de Cobrança');
        $response->assertSee('Mensalidade Abril');
        $response->assertSee('Aviso por E-mail');
        $response->assertSee('Instância do WhatsApp');
        $response->assertSee('vence em');
    }

    public function test_financial_manager_can_send_billing_whatsapp_alert(): void
    {
        Http::fake([
            'https://evolution.example.com/message/sendText/recepcao' => Http::response([
                'status' => 'SENT',
            ], 200),
        ]);

        $admin = User::factory()->create();
        Role::findOrCreate('admin', config('auth.defaults.guard', 'web'));
        $admin->assignRole('admin');

        $student = User::factory()->create([
            'name' => 'João',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '(11) 99999-8888',
            'sexo' => 'M',
            'data_nascimento' => '2000-01-01',
        ]);

        $instance = WhatsappInstance::create([
            'name' => 'Recepção',
            'base_url' => 'https://evolution.example.com',
            'instance_name' => 'recepcao',
            'api_key' => 'secret-key',
            'is_active' => true,
            'is_default' => true,
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade Abril',
            'due_date' => now()->subDays(2)->toDateString(),
            'amount' => 150,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('aluno.whatsapp.billing-alert.send', $student->id), [
                'whatsapp_instance_id' => $instance->id,
                'message' => 'Mensagem de teste',
            ]);

        $response->assertRedirect(route('aluno.show', $student->id) . '#whatsapp-cobranca');
        $response->assertSessionHas('success');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://evolution.example.com/message/sendText/recepcao'
                && $request->hasHeader('apikey', 'secret-key')
                && $request['number'] === '5511999998888'
                && $request['text'] === 'Mensagem de teste';
        });
    }

    public function test_financial_manager_can_send_billing_email_alert(): void
    {
        Mail::fake();

        $admin = User::factory()->create();
        Role::findOrCreate('admin', config('auth.defaults.guard', 'web'));
        $admin->assignRole('admin');

        $student = User::factory()->create([
            'name' => 'João',
            'email' => 'joao@example.com',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '(11) 99999-8888',
            'sexo' => 'M',
            'data_nascimento' => '2000-01-01',
        ]);

        FinancialTransaction::create([
            'kind' => 'conta_receber',
            'user_id' => $student->id,
            'description' => 'Mensalidade Abril',
            'due_date' => now()->subDays(2)->toDateString(),
            'amount' => 150,
            'discount' => 0,
            'addition' => 0,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('aluno.billing.email.send', $student->id), [
                'billing_email_message' => 'Mensagem de cobrança por e-mail.',
            ]);

        $response->assertRedirect(route('aluno.show', $student->id) . '#whatsapp-cobranca');
        $response->assertSessionHas('success');

        Mail::assertSent(BillingAlertEmail::class, function (BillingAlertEmail $mail) use ($student) {
            return $mail->hasTo($student->email)
                && $mail->messageBody === 'Mensagem de cobrança por e-mail.'
                && $mail->transactionDescription === 'Mensalidade Abril';
        });
    }

    public function test_student_show_displays_birthday_greeting_tab_when_birthday_is_today(): void
    {
        $viewer = User::factory()->create();
        $student = User::factory()->create([
            'name' => 'João',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '11999998888',
            'sexo' => 'M',
            'data_nascimento' => now()->format('Y-m-d'),
        ]);

        WhatsappInstance::create([
            'name' => 'Recepção',
            'base_url' => 'https://evolution.example.com',
            'instance_name' => 'recepcao',
            'api_key' => 'secret-key',
            'is_active' => true,
            'is_default' => true,
        ]);

        $response = $this->actingAs($viewer)
            ->get(route('aluno.show', $student->id));

        $response->assertOk();
        $response->assertSee('Hoje é o aniversário de João');
        $response->assertSee('Parabéns por E-mail');
        $response->assertSee('Parabéns por WhatsApp');
    }

    public function test_financial_manager_can_send_birthday_email_and_whatsapp(): void
    {
        Mail::fake();
        Http::fake([
            'https://evolution.example.com/message/sendText/recepcao' => Http::response([
                'status' => 'SENT',
            ], 200),
        ]);

        $admin = User::factory()->create();
        Role::findOrCreate('admin', config('auth.defaults.guard', 'web'));
        $admin->assignRole('admin');

        $student = User::factory()->create([
            'name' => 'João',
            'email' => 'joao@example.com',
        ]);

        Aluno::create([
            'user_id' => $student->id,
            'cpf' => '12345678901',
            'telefone' => '(11) 99999-8888',
            'sexo' => 'M',
            'data_nascimento' => now()->format('Y-m-d'),
        ]);

        $instance = WhatsappInstance::create([
            'name' => 'Recepção',
            'base_url' => 'https://evolution.example.com',
            'instance_name' => 'recepcao',
            'api_key' => 'secret-key',
            'is_active' => true,
            'is_default' => true,
        ]);

        $emailResponse = $this->actingAs($admin)
            ->post(route('aluno.birthday.email.send', $student->id), [
                'birthday_email_message' => 'Feliz aniversário, João!',
            ]);

        $emailResponse->assertRedirect(route('aluno.show', $student->id) . '#birthday-greetings');
        $emailResponse->assertSessionHas('success');

        Mail::assertSent(\App\Mail\BirthdayGreetingMail::class, function ($mail) use ($student) {
            return $mail->hasTo($student->email)
                && $mail->messageBody === 'Feliz aniversário, João!';
        });

        $whatsappResponse = $this->actingAs($admin)
            ->post(route('aluno.birthday.whatsapp.send', $student->id), [
                'birthday_whatsapp_instance_id' => $instance->id,
                'birthday_whatsapp_message' => 'Parabéns pelo seu dia!',
            ]);

        $whatsappResponse->assertRedirect(route('aluno.show', $student->id) . '#birthday-greetings');
        $whatsappResponse->assertSessionHas('success');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://evolution.example.com/message/sendText/recepcao'
                && $request['number'] === '5511999998888'
                && $request['text'] === 'Parabéns pelo seu dia!';
        });
    }
}
