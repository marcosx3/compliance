# Compliance WebApp - Canal de Denúncias

Este projeto é um **webapp de canal de denúncias para compliance**, desenvolvido com Laravel 12, MySQL e suporte a filas para envio de e-mails. O sistema permite que usuários registrem denúncias, enviem comentários e recebam notificações por e-mail, enquanto administradores e moderadores são notificados de forma automática.

---

## Tecnologias Utilizadas

- **Framework:** Laravel 12  
- **Banco de Dados:** MySQL  
- **Fila de Jobs:** Laravel Queue (Redis ou database)  
- **Mail:** Laravel Mail + Queue  
- **Autenticação:** Laravel Breeze / Laravel Fortify (opcional)  
- **Front-end:** Blade com TailwindCSS  

---

## Funcionalidades

1. Registro de denúncias por usuários autenticados ou anônimos.  
2. Suporte a envio de arquivos e respostas a perguntas dinâmicas.  
3. Sistema de comentários em denúncias.  
4. Notificação por e-mail para:  
   - Usuário que registrou a denúncia  
   - Administradores (role: `admin`)  
   - Moderadores ou compliance jurídico (role: `moderator`)  
5. Painel de listagem, detalhamento e atualização de denúncias.  
6. Sistema de protocolos únicos para cada denúncia.  
7. Logs de auditoria para rastrear ações de usuários e administradores.  

---

## Requisitos

- PHP 8.1+  
- Composer  
- MySQL 8+  
- Redis (opcional, para filas mais rápidas)  
- Node.js + npm (para front-end, TailwindCSS)

---

## Instalação

Clone o repositório:

```bash
git clone https://github.com/marcosx3/compliance
cd compliance
