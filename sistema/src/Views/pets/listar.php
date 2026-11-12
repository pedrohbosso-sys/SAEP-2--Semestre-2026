<div class="card">
    <div class="pet-list-heading">
        <h2><?= htmlspecialchars($titulo ?? 'Pets Cadastrados', ENT_QUOTES, 'UTF-8') ?></h2>
        <a href="/pets/novo" class="btn btn-success">+ Cadastrar Pet</a>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Pet</th>
                    <th>Espécie</th>
                    <th>Raça</th>
                    <th>Nascimento</th>
                    <th>Tutor Responsável</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pets)): ?>
                    <tr>
                        <td colspan="6" class="pet-empty-state">
                            Nenhum pet cadastrado no momento.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pets as $pet): ?>
                        <?php
                        $dataNascimento = $pet['data_nascimento'] ?? null;
                        $nascimento = !empty($dataNascimento) && strtotime($dataNascimento) !== false
                            ? date('d/m/Y', strtotime($dataNascimento))
                            : 'Não informado';
                        ?>
                        <tr>
                            <td>#<?= (int)$pet['id'] ?></td>
                            <td><strong><?= htmlspecialchars($pet['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></td>
                            <td><?= htmlspecialchars($pet['especie'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($pet['raca'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $nascimento ?></td>
                            <td><?= htmlspecialchars($pet['tutor_nome'] ?? 'Não informado', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
