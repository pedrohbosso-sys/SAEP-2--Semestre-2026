<div class="card card-form">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <p class="appointment-summary">
        Paciente: <strong><?= htmlspecialchars($agendamento['pet_nome']) ?></strong> | 
        Tutor: <strong><?= htmlspecialchars($agendamento['tutor_nome']) ?></strong>
    </p>

    <form action="/agendamentos/atualizar-data" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <input type="hidden" name="id" value="<?= (int)$agendamento['id'] ?>">

        <div class="form-group">
            <label for="data_agendamento">Data do Agendamento</label>
            <input type="date" id="data_agendamento" name="data_agendamento" value="<?= htmlspecialchars($_POST['data_agendamento'] ?? $agendamento['data_agendamento']) ?>" required>
        </div>

        <div class="form-group">
            <label for="hora_agendamento">Horário</label>
            <input type="time" id="hora_agendamento" name="hora_agendamento" value="<?= htmlspecialchars($_POST['hora_agendamento'] ?? substr($agendamento['hora_agendamento'], 0, 5)) ?>" required>
        </div>

        <div class="form-group">
            <label for="servico">Serviço</label>
            <input type="text" id="servico" name="servico" maxlength="128" value="<?= htmlspecialchars($_POST['servico'] ?? $agendamento['servico']) ?>" required>
        </div>

        <div class="form-actions">
            <a href="/agendamentos" class="link-back">← Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </div>
    </form>
</div>