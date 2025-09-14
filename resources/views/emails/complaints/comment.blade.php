@component('mail::message')
# 📢 Novo comentário na sua denúncia

Olá **{{ $data["NomeDenunciante"] ?? 'Usuário' }}**,  

Um novo comentário foi adicionado em sua denúncia.

---

**Protocolo:** `{{ $data["protocol"] }}`  
**Título:** {{ $data["title"] ?? 'Sem título' }}

@isset($data["response"])
> _"{{ $data["response"] }}"_
@endisset

---

@component('mail::button', ['url' => route('complaints.show', $data["protocol"])])
🔎 Ver denúncia completa
@endcomponent

Obrigado por utilizar o **{{ config('app.name') }}**.  
Estamos acompanhando sua denúncia com atenção.

Atenciosamente,  
Equipe **{{ config('app.name') }}**
@endcomponent
