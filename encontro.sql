-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/11/2023 às 05:51
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `encontro`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `sala` varchar(12) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `data_evento` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `evento`
--

INSERT INTO `evento` (`id`, `nome`, `sala`, `idUsuario`, `data_evento`) VALUES
(6, 'Java Spring Boot', 'C205', 9, '2024-01-12 03:00:00'),
(7, 'Microsservices', 'C106', 9, '2023-12-01 21:45:00'),
(8, 'Machine Learning', 'C102', 9, '2023-12-20 15:45:00'),
(9, 'Cloud Computing', 'C202', 9, '2023-12-07 20:45:00'),
(16, 'Estrutura de Dados', 'C101', 14, '2024-01-02 15:45:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `participacao`
--

CREATE TABLE `participacao` (
  `idUsuario` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL,
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `participacao`
--

INSERT INTO `participacao` (`idUsuario`, `idEvento`, `data_inscricao`) VALUES
(9, 7, '0000-00-00 00:00:00'),
(11, 6, '2023-11-26 03:00:00'),
(11, 7, '2023-11-26 03:00:00'),
(11, 8, '2023-11-26 03:00:00'),
(11, 9, '2023-11-26 03:00:00'),
(12, 6, '2023-11-26 03:00:00'),
(12, 7, '2023-11-26 03:00:00'),
(12, 8, '2023-11-26 03:00:00'),
(12, 9, '2023-11-26 03:00:00'),
(14, 6, '2017-01-23 04:24:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `datanasc` date NOT NULL,
  `username` varchar(30) NOT NULL,
  `senha` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `datanasc`, `username`, `senha`) VALUES
(9, 'Adriann', '2003-11-12', 'adriann', '$2y$10$psGOZHV5zYvExuyJ2lSFp.0qru.NCqiJ7f8yxbYZwnkroN4lRxLR.'),
(10, 'teste', '1995-11-11', 'teste', '$2y$12$6E6TrTgNKfg3WaOZS6YB6.SpFPs1/hDHCs2CBzJTEipL5ZoR7BvZ6'),
(11, 'Francisco', '2023-01-01', 'fran123', '$2y$12$.q3l9Enx38vRv/MGVpSAlez0pBSQ83P4xSPlrbzHVjA8xnD02F.Pq'),
(12, 'Joandson', '2002-02-02', 'jhow123', '$2y$12$KlJPPKqgRDV2gkO7AJXKMeo6NiDo3mPhy.FyFnE1LSHVTLzpIhaom'),
(14, 'Emanuel', '1997-12-20', 'emanu123', '$2y$10$1JQv9A23MoGaspeGm76r/uRq8setLyDuQUJjXC23g2uqTNkZnpfwm');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `evento_ibfk_1` (`idUsuario`);

--
-- Índices de tabela `participacao`
--
ALTER TABLE `participacao`
  ADD PRIMARY KEY (`idUsuario`,`idEvento`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idEvento` (`idEvento`),
  ADD KEY `idUsuario_2` (`idUsuario`),
  ADD KEY `idEvento_2` (`idEvento`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `participacao`
--
ALTER TABLE `participacao`
  ADD CONSTRAINT `participacao_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `participacao_ibfk_2` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
