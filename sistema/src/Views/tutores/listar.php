<div class="card">
    <div class="flex-between">
        <h2><?= htmlspecialchars($titulo) ?></h2>
        <a href="/tutores/novo" class="btn btn-success">+ Cadastrar Tutor</a>
    </div>

    <form action="/tutores" method="GET" class="search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($busca ?? '') ?>" placeholder="Pesquise por nome, e-mail ou telefone..." class="search-input">
        <button type="submit" class="btn">Buscar</button>
        <?php if (!empty($busca)): ?>
            <a href="/tutores" class="btn btn-danger button-link">Limpar</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome do Tutor</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th class="table-actions-heading">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tutores)): ?>
                <tr><td colspan="5" class="table-empty">Nenhum tutor encontrado para o termo pesquisado.</td></tr>
            <?php else: ?>
                <?php foreach ($tutores as $tutor): ?>
                    <tr>
                        <td>#<?= (int)$tutor['id'] ?></td>
                        <td><strong><?= htmlspecialchars($tutor['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($tutor['telefone']) ?></td>
                        <td><?= htmlspecialchars($tutor['email']) ?></td>
                        <td class="table-actions-cell">
                            <a href="/tutores/editar?id=<?= (int)$tutor['id'] ?>" class="btn tutor-edit-button">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>