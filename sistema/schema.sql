CREATE DATABASE saep_prova;

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(32) NOT NULL CHECK (perfil IN ('admin', 'veterinario', 'recepcionista')),
    criado_em TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tutores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(128) NOT NULL,
    cpf_criptografado VARCHAR(255),
    telefone VARCHAR(32) NOT NULL,
    email VARCHAR(128) NOT NULL,
    criado_em TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pets (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id) ON DELETE RESTRICT,
    nome VARCHAR(128) NOT NULL,
    especie VARCHAR(64) NOT NULL,
    raca VARCHAR(128) NOT NULL,
    data_nascimento DATE NOT NULL,
    cadastrado_em TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (id, tutor_id)
);

CREATE TABLE IF NOT EXISTS agendamentos (
    id SERIAL PRIMARY KEY,
    tutor_id INTEGER NOT NULL REFERENCES tutores(id) ON DELETE RESTRICT,
    pet_id INTEGER NOT NULL REFERENCES pets(id) ON DELETE RESTRICT,
    usuario_id INTEGER NOT NULL REFERENCES usuarios(id) ON DELETE RESTRICT,
    data_agendamento DATE NOT NULL,
    hora_agendamento TIME NOT NULL,
    servico VARCHAR(128) NOT NULL,
    status VARCHAR(16) NOT NULL DEFAULT 'AGENDADO'
        CHECK (status IN ('AGENDADO', 'CONCLUIDO', 'CANCELADO')),
    observacao TEXT,
    criado_em TIMESTAMP WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT agendamento_pet_tutor_fk
        FOREIGN KEY (pet_id, tutor_id) REFERENCES pets(id, tutor_id)
        DEFERRABLE INITIALLY DEFERRED
);

CREATE INDEX IF NOT EXISTS agendamentos_data_hora_idx
    ON agendamentos (data_agendamento, hora_agendamento);
CREATE INDEX IF NOT EXISTS tutores_nome_idx ON tutores (nome);


INSERT INTO usuarios (id, nome, email, senha_hash, perfil) VALUES
(1, 'Camila Alencar', 'camila.vet@sisvet.com', '$2y$12$0X7TwogiQBTCpTXauVX.cOA4HBwQpgpHapy2UsP2RRbjfO9Pnpi.S', 'veterinario'),
(2, 'Lucas Fernandes', 'lucas.recepcao@sisvet.com', '$2y$12$u8ejrtVgRO9mZx2mfWSBFOqHlIAsU8ZZEnMaaOqszdqC6jPak7qrG', 'recepcionista'),
(3, 'Mariana Rocha', 'gestao@sisvet.com', '$2y$12$u8ejrtVgRO9mZx2mfWSBFOqHlIAsU8ZZEnMaaOqszdqC6jPak7qrG', 'admin')
ON CONFLICT (id) DO NOTHING;

INSERT INTO tutores (id, nome, cpf_criptografado, telefone, email) VALUES
(1, 'Carlos Eduardo Silva', NULL, '(11) 98765-4321', 'carlos.silva@email.com'),
(2, 'Beatriz Souza Martins', NULL, '(11) 97654-3210', 'beatriz.martins@email.com'),
(3, 'Fernando Henrique Lima', NULL, '(19) 99123-4567', 'fernando.lima@email.com')
ON CONFLICT (id) DO NOTHING;

INSERT INTO pets (id, tutor_id, nome, especie, raca, data_nascimento) VALUES
(1, 1, 'Thor', 'Canina', 'Golden Retriever', '2021-05-10'),
(2, 2, 'Mia', 'Felina', 'Siamês', '2022-08-15'),
(3, 3, 'Bob', 'Canina', 'Bulldog Francês', '2020-02-20')
ON CONFLICT (id) DO NOTHING;

INSERT INTO agendamentos
    (id, tutor_id, pet_id, usuario_id, data_agendamento, hora_agendamento, servico, status, observacao)
VALUES
(1, 1, 1, 1, '2026-11-15', '09:30:00', 'Consulta geral e vacinação', 'AGENDADO', 'Carteira de vacinação a conferir'),
(2, 2, 2, 1, '2026-11-20', '14:00:00', 'Consulta odontológica', 'AGENDADO', 'Avaliar saúde bucal'),
(3, 3, 3, 2, '2026-12-10', '10:30:00', 'Check-up preventivo', 'AGENDADO', 'Tutor solicitou exames de rotina')
ON CONFLICT (id) DO NOTHING;

SELECT setval('usuarios_id_seq', COALESCE((SELECT MAX(id) FROM usuarios), 1));
SELECT setval('tutores_id_seq', COALESCE((SELECT MAX(id) FROM tutores), 1));
SELECT setval('pets_id_seq', COALESCE((SELECT MAX(id) FROM pets), 1));
SELECT setval('agendamentos_id_seq', COALESCE((SELECT MAX(id) FROM agendamentos), 1));
