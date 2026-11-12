<div class="card card-form">
    <h2><?= htmlspecialchars($titulo ?? 'Cadastrar Pet', ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="/pets/salvar" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label for="tutor_id">Tutor Responsável *</label>
            <select id="tutor_id" name="tutor_id" required>
                <option value="">-- Selecione o Tutor --</option>
                <?php foreach (($tutores ?? []) as $tutor): ?>
                    <option
                        value="<?= (int)$tutor['id'] ?>"
                        <?= (isset($_POST['tutor_id']) && $_POST['tutor_id'] == $tutor['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($tutor['nome'], ENT_QUOTES, 'UTF-8') ?>
                        (<?= htmlspecialchars($tutor['telefone'], ENT_QUOTES, 'UTF-8') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nome">Nome do Pet *</label>
            <input
                type="text"
                id="nome"
                name="nome"
                required
                maxlength="100"
                placeholder="Ex.: Thor, Mia, Pipoca"
                value="<?= htmlspecialchars($_POST['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="especie">Espécie *</label>
            <select id="especie" name="especie" required>
                <option value="">-- Selecione a Espécie --</option>
                <?php
                $especies = ['Canina', 'Felina', 'Aves', 'Roedores'];
                $especieSelecionada = $_POST['especie'] ?? '';
                ?>
                <?php foreach ($especies as $especie): ?>
                    <option
                        value="<?= htmlspecialchars($especie, ENT_QUOTES, 'UTF-8') ?>"
                        <?= $especieSelecionada === $especie ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($especie, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="raca">Raça *</label>
            <input
                type="text"
                id="raca"
                name="raca"
                required
                maxlength="100"
                placeholder="Ex.: Labrador, Siamês, SRD"
                value="<?= htmlspecialchars($_POST['raca'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento *</label>
            <input
                type="date"
                id="data_nascimento"
                name="data_nascimento"
                required
                max="<?= date('Y-m-d') ?>"
                value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-actions">
            <a href="/pets" class="link-back">← Voltar</a>
            <button type="submit" class="btn btn-success">Cadastrar Pet</button>
        </div>
    </form>
</div>
