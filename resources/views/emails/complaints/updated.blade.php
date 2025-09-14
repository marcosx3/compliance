@component('mail::message')
# 🔔 Status da denúncia atualizado

Olá **{{ $data["NomeDenunciante"] ?? 'Usuário' }}**,  

O status da sua denúncia foi atualizado. Confira os detalhes:

---

**Protocolo:** `{{ $data["protocol"] }}`  
**Título:** {{ $data["title"] }}  

@if(!empty($data["description"]))
**Descrição:**  
> _"{{ $data["description"] }}"_
@endif

**Novo Status:**  
@component('mail::panel')
{{ $data["status"] }}
@endcomponent

---

@component('mail::button', ['url' => route('complaints.show', $data["protocol"])])
📌 Acompanhar andamento
@endcomponent

Estamos monitorando sua denúncia e informaremos sobre novas atualizações.  

Obrigado pela confiança,  
Equipe **{{ config('app.name') }}**
@endcomponent
