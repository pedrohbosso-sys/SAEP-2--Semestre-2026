<div class="card login-card">
    <h2 class="login-title"><?= htmlspecialchars($titulo) ?></h2>
    <p class="login-intro">Insira suas credenciais de acesso</p>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <div class="form-group">
            <label for="email">E-mail Corporativo</label>
            <input type="email" id="email" name="email" required placeholder="seu.email@vetcare.com">
        </div>
        <div class="form-group">
            <label for="senha">Senha de Acesso</label>
            <input type="password" id="senha" name="senha" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn login-submit">Autenticar no Sistema</button>
    </form>
    <div class="login-hint">
        Sessão com expiração automática por inatividade após 15 minutos.
    </div>
</div>