<div class="card">
    <h2><?= htmlspecialchars($titulo) ?></h2>
    <p class="texto-secundario">
        Bem-vindo(a), <strong><?= htmlspecialchars($usuarioNome) ?></strong>. Selecione uma das opções abaixo para gerenciar a clínica:
    </p>

    <div class="grid-stats">
        <div class="stat-box">
            <span>Tutores Cadastrados</span>
            <strong><?= (int)$totalTutores ?></strong>
            <a href="/tutores" class="btn stat-link">Gerenciar Tutores</a>
        </div>
        <div class="stat-box">
            <span>Pets Registrados</span>
            <strong><?= (int)$totalPets ?></strong>
            <a href="/pets" class="btn stat-link">Gerenciar Pets</a>
        </div>
        <div class="stat-box">
            <span>Agendamentos</span>
            <strong class="texto-destaque">Consultas</strong>
            <a href="/agendamentos" class="btn btn-success stat-link">Ver Agendamentos</a>
        </div>
    </div>

    <h3 class="dashboard-section-title">Próximos Atendimentos</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Paciente (Pet)</th>
                <th>Tutor Responsável</th>
                <th>Procedimento</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($agendamentosRecentes)): ?>
                <tr><td colspan="5" class="empty-state">Nenhum agendamento recente.</td></tr>
            <?php else: ?>
                <?php foreach ($agendamentosRecentes as $agendamento): ?>
                    <tr>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($agendamento['data_agendamento']))) ?></td>
                        <td><?= htmlspecialchars(substr($agendamento['hora_agendamento'], 0, 5)) ?></td>
                        <td><strong><?= htmlspecialchars($agendamento['pet_nome']) ?></strong> (<?= htmlspecialchars($agendamento['pet_especie']) ?>)</td>
                        <td><?= htmlspecialchars($agendamento['tutor_nome']) ?></td>
                        <td><?= htmlspecialchars($agendamento['servico']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>