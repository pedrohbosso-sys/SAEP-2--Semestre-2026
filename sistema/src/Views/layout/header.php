<?php
$usuarioLogado = $_SESSION['usuario_nome'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'VetCare — Clínica Veterinária') ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <script>
        const temaSalvo = localStorage.getItem('sisvet-tema');
        document.documentElement.dataset.theme = temaSalvo === 'dark' ? 'dark' : 'light';
    </script>
</head>
<body>
<header>
    <h1>VetCare — Gestão Veterinária</h1>
    <nav>
        <button type="button" class="theme-toggle" id="theme-toggle" aria-pressed="false">☾ Modo escuro</button>
        <?php if ($usuarioLogado): ?>
            <a href="/">Painel</a>
            <a href="/tutores">Tutores</a>
            <a href="/pets">Pets</a>
            <a href="/agendamentos">Agendamentos</a>
            <span class="user-tag">👤 <?= htmlspecialchars($usuarioLogado) ?></span>
            <form action="/logout" method="POST" class="logout-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <button type="submit" class="btn btn-danger btn-small">Sair</button>
            </form>
        <?php else: ?>
            <a href="/login" class="btn">Entrar</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
<script>
    const botaoTema = document.getElementById('theme-toggle');
    const atualizarBotaoTema = () => {
        const temaEscuro = document.documentElement.dataset.theme === 'dark';
        botaoTema.textContent = temaEscuro ? '☀ Modo claro' : '☾ Modo escuro';
        botaoTema.setAttribute('aria-pressed', String(temaEscuro));
    };

    botaoTema.addEventListener('click', () => {
        const novoTema = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = novoTema;
        localStorage.setItem('sisvet-tema', novoTema);
        atualizarBotaoTema();
    });

    atualizarBotaoTema();
</script>