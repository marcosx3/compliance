import './bootstrap';
import Swal from 'sweetalert2';


 document.addEventListener('DOMContentLoaded', function() {
            // Menu mobile
                

            // Toggle identificação
            const identificarRadios = document.querySelectorAll('input[name="identificar"]');
            const dadosIdentificacao = document.getElementById('dados-identificacao');
            
            identificarRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    dadosIdentificacao.classList.toggle('hidden', this.value === 'nao');
                });
            });

            // Simular login admin
            const adminLink = document.getElementById('admin-link');
            const adminSection = document.getElementById('admin');

            
            const statusResult = document.getElementById('status-result');
            
                      // Carregar perguntas do formulário
            const perguntasContainer = document.getElementById('perguntas-container');
            const perguntas = [
                {
                    tipo: 'dropdown',
                    pergunta: 'Setor/Área relacionada à denúncia',
                    opcoes: ['RH', 'Financeiro', 'Operações', 'TI', 'Comercial', 'Outro']
                },
                {
                    tipo: 'checkbox',
                    pergunta: 'Tipo de irregularidade',
                    opcoes: ['Conduta inadequada', 'Assédio', 'Fraude', 'Corrupção', 'Discriminação', 'Outro']
                },
                {
                    tipo: 'text',
                    pergunta: 'Local onde ocorreu a situação'
                },
                {
                    tipo: 'date',
                    pergunta: 'Data aproximada do ocorrido'
                }
            ];

          

            // Simular envio de formulário
            const form = document.getElementById('denuncia-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const protocolo = 'DN' + new Date().getFullYear() + Math.random().toString().substr(2, 6);
                alert(`Denúncia enviada com sucesso!\nSeu protocolo é: ${protocolo}\nGuarde este número para acompanhar o status.`);
                form.reset();
            });

            // Smooth scroll
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });



// Tornar disponível globalmente
window.Swal = Swal;

// Opcional: Configurar um tema padrão
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});