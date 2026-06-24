-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/06/2026 às 21:14
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rinhadecoisas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `batalhas`
--

CREATE TABLE `batalhas` (
  `id` int(11) NOT NULL,
  `data_batalha` timestamp NOT NULL DEFAULT current_timestamp(),
  `resultado` varchar(10) NOT NULL,
  `recompensa` decimal(10,2) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `comprador_id` int(11) NOT NULL,
  `lutador_id` int(11) NOT NULL,
  `data_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `valor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lutadores`
--

CREATE TABLE `lutadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ataque` int(11) NOT NULL,
  `defesa` int(11) NOT NULL,
  `velocidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criador_id` int(11) NOT NULL,
  `imagem` varchar(255) DEFAULT 'padrao.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lutador_batalha`
--

CREATE TABLE `lutador_batalha` (
  `lutador_id` int(11) NOT NULL,
  `batalha_id` int(11) NOT NULL,
  `equipe` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `saldo` decimal(10,2) DEFAULT 500.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `batalhas`
--
ALTER TABLE `batalhas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comprador_id` (`comprador_id`),
  ADD KEY `lutador_id` (`lutador_id`);

--
-- Índices de tabela `lutadores`
--
ALTER TABLE `lutadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `criador_id` (`criador_id`);

--
-- Índices de tabela `lutador_batalha`
--
ALTER TABLE `lutador_batalha`
  ADD PRIMARY KEY (`lutador_id`,`batalha_id`),
  ADD KEY `batalha_id` (`batalha_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `batalhas`
--
ALTER TABLE `batalhas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `lutadores`
--
ALTER TABLE `lutadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `batalhas`
--
ALTER TABLE `batalhas`
  ADD CONSTRAINT `batalhas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`comprador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`lutador_id`) REFERENCES `lutadores` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `lutadores`
--
ALTER TABLE `lutadores`
  ADD CONSTRAINT `lutadores_ibfk_1` FOREIGN KEY (`criador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `lutador_batalha`
--
ALTER TABLE `lutador_batalha`
  ADD CONSTRAINT `lutador_batalha_ibfk_1` FOREIGN KEY (`lutador_id`) REFERENCES `lutadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lutador_batalha_ibfk_2` FOREIGN KEY (`batalha_id`) REFERENCES `batalhas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
