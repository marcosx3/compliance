<?php

namespace Tests\Feature;

use App\Mail\AdminComplaintRegisteredMail;
use App\Mail\ComplaintRegisteredMail;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\App\Http\Controllers\ComplaintController::class)]
class ComplaintControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;
    private User $moderator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);

        Mail::fake();
        Storage::fake('public');

        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com']);
        $this->moderator = User::factory()->create(['role' => 'moderator', 'email' => 'mod@example.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_index_displays_complaints(): void
    {
        Complaint::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('complaints.index'));

        $response->assertStatus(200);
        $response->assertViewIs('compliant.index');
        $response->assertViewHas('complaints');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_store_creates_complaint_and_sends_emails_to_user_and_admin(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('complaints.store'), [
            'title' => 'Teste de denúncia',
            'description' => 'Descrição da denúncia',
            'compliance_juridico' => 'N',
            'answers' => [],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'title' => 'Teste de denúncia',
            'description' => 'Descrição da denúncia',
        ]);

        Mail::assertQueued(ComplaintRegisteredMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });

        Mail::assertQueued(AdminComplaintRegisteredMail::class, function ($mail) {
            return $mail->hasTo($this->admin->email);
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_store_sends_email_to_moderator_if_compliance_is_s(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('complaints.store'), [
            'title' => 'Teste Jurídico',
            'description' => 'Descrição jurídico',
            'compliance_juridico' => 'S',
            'answers' => [],
        ]);

        $response->assertRedirect();

        Mail::assertQueued(ComplaintRegisteredMail::class, function ($mail) {
            return $mail->hasTo($this->moderator->email);
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_update_changes_status_and_sends_emails(): void
    {
        $this->actingAs($this->user);
        $complaint = Complaint::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'ABERTA',
            'compliance' => 'N',
        ]);

        $response = $this->put(route('complaints.update', $complaint->id), [
            'status' => 'EM_ANALISE',
            'response' => 'Comentário de teste',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'EM_ANALISE',
        ]);

        Mail::assertQueued(\App\Mail\ComplaintUpdatedMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });

        Mail::assertQueued(\App\Mail\AdminComplaintUpdatedMail::class, function ($mail) {
            return $mail->hasTo($this->admin->email);
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_comment_creates_response_and_sends_emails(): void
    {
        $this->actingAs($this->user);
        $complaint = Complaint::factory()->create(['user_id' => $this->user->id, 'compliance' => 'N']);

        $response = $this->post(route('complaints.comment', $complaint->id), [
            'response' => 'Novo comentário',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('complaint_responses', [
            'complaint_id' => $complaint->id,
            'response' => 'Novo comentário',
            'user_id' => $this->user->id,
        ]);

        Mail::assertQueued(\App\Mail\ComplaintCommentMail::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });

        Mail::assertQueued(\App\Mail\AdminComplaintCommentMail::class, function ($mail) {
            return $mail->hasTo($this->admin->email);
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_consulta_returns_complaint_or_site_view(): void
    {
        $complaint = Complaint::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get(route('complaints.consulta', ['protocol' => $complaint->protocol]));
        $response->assertStatus(200);
        $response->assertViewIs('compliant.consulta');
        $response->assertViewHas('complaint');

        $response = $this->get(route('complaints.consulta', ['protocol' => ''])); 
        $response->assertStatus(200);
        $response->assertViewIs('site');
    }
}
