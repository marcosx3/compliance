@component('mail::message')
# ✅ Denúncia registrada com sucesso

Olá **{{ $data["NomeDenunciante"] ?? 'Usuário' }}**,  

Recebemos sua denúncia e ela foi registrada com sucesso no sistema.  
Aqui estão os detalhes:

---

**Protocolo:** `{{ $data["protocol"] }}`  
**Título:** {{ $data["title"] }}  

@if(!empty($data["description"]))
**Descrição:**  
> _"{{ $data["description"] }}"_
@endif

---

@component('mail::button', ['url' => route('complaints.show', $data["protocol"])])
🔎 Acompanhar minha denúncia
@endcomponent

Acompanhe o andamento sempre que quiser pelo portal do **{{ config('app.name') }}**.  

Obrigado pela confiança,  
Equipe **{{ config('app.name') }}**
@endcomponent
