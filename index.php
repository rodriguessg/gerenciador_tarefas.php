<?php
session_start();

// Função para ler as tarefas do arquivo
function getTasks() {
    $file = 'tasks.txt';
    if (file_exists($file)) {
        return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    return [];
}

// Função para salvar a tarefa no arquivo
function saveTask($task) {
    $file = 'tasks.txt';
    file_put_contents($file, $task . PHP_EOL, FILE_APPEND);
}

// Função para deletar a tarefa do arquivo
function deleteTask($taskToDelete) {
    $file = 'tasks.txt';
    $tasks = getTasks();
    $tasks = array_filter($tasks, function($task) use ($taskToDelete) {
        return trim($task) !== trim($taskToDelete);
    });
    file_put_contents($file, implode(PHP_EOL, $tasks) . PHP_EOL);
}

// Função para atualizar uma tarefa
function updateTask($oldTask, $newTask) {
    $file = 'tasks.txt';
    $tasks = getTasks();
    $tasks = array_map(function($task) use ($oldTask, $newTask) {
        return trim($task) === trim($oldTask) ? $newTask : $task;
    }, $tasks);
    file_put_contents($file, implode(PHP_EOL, $tasks) . PHP_EOL);
}

// Verifica se o formulário para adicionar tarefa foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['description']) && !empty($_POST['description'])) {
        $newTask = htmlspecialchars($_POST['description']); // Sanitização do input
        saveTask($newTask); // Salva a nova tarefa
        header('Location: ' . $_SERVER['PHP_SELF']); // Redireciona para evitar reenvio do formulário
        exit;
    }

    // Verifica se há uma tarefa para deletar
    if (isset($_POST['delete_task'])) {
        deleteTask(htmlspecialchars($_POST['delete_task']));
        header('Location: ' . $_SERVER['PHP_SELF']); // Redireciona para evitar reenvio do formulário
        exit;
    }

    // Verifica se há uma tarefa para atualizar
    if (isset($_POST['edit_task']) && isset($_POST['new_description'])) {
        updateTask(htmlspecialchars($_POST['edit_task']), htmlspecialchars($_POST['new_description']));
        header('Location: ' . $_SERVER['PHP_SELF']); // Redireciona para evitar reenvio do formulário
        exit;
    }
}

// Obter as tarefas salvas
$tasks = getTasks();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>To do list </title>
</head>
<body>
    <div id="to_do">
        <h1>Minhas Tarefas</h1>

        <!-- Formulário para adicionar nova tarefa -->
        <form action="" method="POST" class="to_do_form">
            <input type="text" name="description" placeholder="Digite sua nova tarefa" required>
            <button type="submit" class="form-button">
                <i class="fa-solid fa-circle-plus"></i>
            </button>
        </form>

        <div id="tasks">
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="task">
                        <input type="checkbox" class="progress">
                        <p class="task-description">
                            <?php echo htmlspecialchars($task); ?>
                        </p>
                        <div class="task-actions">
                            <!-- Botão para editar a tarefa -->
                            <a class="action-button edit-button" href="#" data-task="<?php echo htmlspecialchars($task); ?>">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <!-- Botão para excluir a tarefa -->
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_task" value="<?php echo htmlspecialchars($task); ?>">
                                <button type="submit" class="action-button delete-button">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Você ainda não tem tarefas.</p>
            <?php endif; ?>
        </div>
    </div>

   <!-- Fundo opaco para o formulário de edição -->
<div id="edit_task_overlay"></div>

<!-- Formulário oculto para editar tarefas -->
<div id="edit_task_form">
    <h2>Editar Tarefa</h2>
    <form action="" method="POST">
        <input type="hidden" name="edit_task" id="edit_task_old_value">
        <input type="text" name="new_description" id="edit_task_new_value" placeholder="Editar tarefa" required>
        <button type="submit" class="form-button confirm-button">
            <i class="fa-solid fa-check"></i>
        </button>
    </form>
</div>

    <script src="script.js"></script>
    <script>
        // Script para lidar com a exibição do formulário de edição
        $(document).ready(function() {
            $('.edit-button').on('click', function(e) {
                e.preventDefault();
                var task = $(this).data('task');
                $('#edit_task_old_value').val(task);
                $('#edit_task_new_value').val(task);
                $('#edit_task_form').show();
            });
        });
    </script>
</body>
</html>