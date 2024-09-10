$(document).ready(function() {
    // Mostra o formulário de edição ao clicar no botão de edição
    $('.edit-button').on('click', function(e) {
        e.preventDefault();
        var task = $(this).data('task'); // Pega a tarefa atual
        $('#edit_task_old_value').val(task); // Define o valor da tarefa atual no input oculto
        $('#edit_task_new_value').val(task); // Preenche o campo com a tarefa atual para edição
        $('#edit_task_form').show(); // Exibe o formulário de edição
        $('#edit_task_overlay').show(); // Exibe o fundo opaco
    });

    // Esconde o formulário de edição ao clicar fora dele (overlay)
    $('#edit_task_overlay').on('click', function() {
        $('#edit_task_form').hide(); // Esconde o formulário
        $('#edit_task_overlay').hide(); // Esconde o fundo opaco
    });

    // Oculta o formulário e o overlay ao submeter o formulário de edição
    $('#edit_task_form form').on('submit', function() {
        $('#edit_task_form').hide();
        $('#edit_task_overlay').hide();
    });

    // Esconde o formulário de edição ao pressionar a tecla 'Esc'
    $(document).on('keydown', function(e) {
        if (e.key === "Escape") { 
            $('#edit_task_form').hide();
            $('#edit_task_overlay').hide();
        }
    });
});
