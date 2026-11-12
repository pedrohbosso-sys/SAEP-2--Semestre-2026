<div class="card">
    <div class="section-heading">
        <div>
            <h2><?= htmlspecialchars($titulo) ?></h2>
            <p class="section-description">
                Atendimentos em ordem cronológica, com os dados de contato do tutor e os dados do pet.
            </p>
        </div>
        <a href="/agendamentos/novo" class="btn btn-success">+ Novo Agendamento</a>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Data / Hora</th>
                    <th>Pet</th>
                    <th>Tutor</th>
                    <th>Serviço / Status</th>
                    <th>Agendado por</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($agendamentos)): ?>
                    <tr><td colspan="6" class="table-empty">Nenhum agendamento cadastrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($agendamentos as $agendamento): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars(date('d/m/Y', strtotime($agendamento['data_agendamento']))) ?></strong>
                                <?= htmlspecialchars(substr($agendamento['hora_agendamento'], 0, 5)) ?>
                            </td>
                            <td>
                                <small>#Pet <?= (int)$agendamento['pet_id'] ?></small><br>
                                <strong><?= htmlspecialchars($agendamento['pet_nome']) ?></strong><br>
                                <?= htmlspecialchars($agendamento['pet_especie']) ?> — <?= htmlspecialchars($agendamento['pet_raca']) ?><br>
                                Nascimento: <?= htmlspecialchars(date('d/m/Y', strtotime($agendamento['pet_data_nascimento']))) ?>
                            </td>
                            <td>
                                <small>#Tutor <?= (int)$agendamento['tutor_id'] ?></small><br>
                                <strong><?= htmlspecialchars($agendamento['tutor_nome']) ?></strong><br>
                                <?= htmlspecialchars($agendamento['tutor_telefone']) ?><br>
                                <?= htmlspecialchars($agendamento['tutor_email']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($agendamento['servico']) ?><br>
                                <small><?= htmlspecialchars($agendamento['status']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($agendamento['responsavel_nome']) ?></td>
                            <td>
                                <div class="agendamento-acoes">
                                <a href="/agendamentos/editar-data?id=<?= (int)$agendamento['id'] ?>" class="btn btn-success">
                                    Editar
                                </a>
                                <?php if ($agendamento['status'] === 'AGENDADO'): ?>
                                    <form action="/agendamentos/concluir" method="POST">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                        <input type="hidden" name="id" value="<?= (int)$agendamento['id'] ?>">
                                        <button type="submit" class="btn btn-success">
                                            Concluir
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="/agendamentos/excluir" method="POST" onsubmit="return confirm('Deseja realmente excluir este agendamento?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int)$agendamento['id'] ?>">
                                    <button type="submit" class="btn btn-danger">
                                        Excluir
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
