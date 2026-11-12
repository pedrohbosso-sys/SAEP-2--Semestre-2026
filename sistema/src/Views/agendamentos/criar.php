<div class="card card-form">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/agendamentos/salvar" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <div class="form-group">
            <label for="tutor_id">Tutor Responsável</label>
            <select id="tutor_id" name="tutor_id" required>
                <option value="">-- Selecione o Tutor --</option>
                <?php foreach ($tutores as $t): ?>
                    <option value="<?= (int)$t['id'] ?>" <?= (int)($_POST['tutor_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pet_id">Pet / Paciente</label>
            <select id="pet_id" name="pet_id" required>
                <option value="">-- Selecione o Pet --</option>
                <?php foreach ($pets as $p): ?>
                    <option value="<?= (int)$p['id'] ?>" <?= (int)($_POST['pet_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome']) ?> (Tutor: <?= htmlspecialchars($p['tutor_nome']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_agendamento">Data da Consulta</label>
            <input type="date" id="data_agendamento" name="data_agendamento" required value="<?= htmlspecialchars($_POST['data_agendamento'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="hora_agendamento">Horário</label>
            <input type="time" id="hora_agendamento" name="hora_agendamento" required value="<?= htmlspecialchars($_POST['hora_agendamento'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="servico">Serviço Solicitado</label>
            <input type="text" id="servico" name="servico" required placeholder="Ex.: Consulta de Rotina, Vacinação, Castração" value="<?= htmlspecialchars($_POST['servico'] ?? '') ?>">
        </div>
        <div class="form-actions">
            <a href="/agendamentos" class="link-back">← Voltar</a>
            <button type="submit" class="btn btn-success">Salvar Agendamento</button>
        </div>
    </form>
</div>