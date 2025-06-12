-- Seleciona o banco de dados
USE `crud_db`;

-- Criação da tabela de exemplo (adapte conforme seu CRUD)
CREATE TABLE IF NOT EXISTS `items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(255) NOT NULL,
    `descricao` TEXT,
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserção de dados de exemplo (opcional, remova ou adapte)
INSERT INTO `items` (`nome`, `descricao`) VALUES
('Item Exemplo 1', 'Esta é a descrição do item 1.'),
('Item Exemplo 2', 'Esta é a descrição do item 2.'),
('Item Exemplo 3', 'Esta é a descrição do item 3.');
ON DUPLICATE KEY UPDATE nome=VALUES(nome);
