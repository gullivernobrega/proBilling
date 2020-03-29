-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 12-Mar-2020 às 10:05
-- Versão do servidor: 5.5.64-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `probilling`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `activation`
--

CREATE TABLE IF NOT EXISTS `activation` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `mac` text NOT NULL,
  `licensa` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `activation`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `activecalls`
--

CREATE TABLE IF NOT EXISTS `activecalls` (
  `id` int(11) NOT NULL,
  `canal` varchar(80) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `tronco` varchar(50) DEFAULT NULL,
  `ndiscado` varchar(25) DEFAULT '0',
  `codec` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` varchar(16) NOT NULL,
  `duration` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `clid` varchar(250) NOT NULL,
  `src` varchar(20) NOT NULL,
  `dst` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `activecalls_torpedo`
--

CREATE TABLE IF NOT EXISTS `activecalls_torpedo` (
  `id` int(11) NOT NULL,
  `ramal` varchar(20) DEFAULT NULL,
  `nomedocliente` varchar(50) DEFAULT NULL,
  `cpf_cnpj` varchar(14) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `duracao` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `agenda_id` int(11) NOT NULL,
  `agenda_nome` varchar(100) DEFAULT NULL,
  `agenda_descricao` text,
  `agenda_status` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda_sms`
--

CREATE TABLE IF NOT EXISTS `agenda_sms` (
  `agenda_sms_id` int(11) NOT NULL,
  `agenda_sms_nome` varchar(200) DEFAULT NULL,
  `agenda_sms_status` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents`
--

CREATE TABLE IF NOT EXISTS `agents` (
  `agent_id` int(11) NOT NULL,
  `agent_user` varchar(50) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `agent_pass` varchar(100) NOT NULL,
  `agent_pause` varchar(250) DEFAULT NULL,
  `agent_pause_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents_pause`
--

CREATE TABLE IF NOT EXISTS `agents_pause` (
  `id` int(11) NOT NULL,
  `tipo` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents_status`
--

CREATE TABLE IF NOT EXISTS `agents_status` (
  `id` int(11) NOT NULL,
  `agente` varchar(250) NOT NULL,
  `ramal` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `channel` varchar(250) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `codigo` varchar(250) DEFAULT NULL,
  `fila` varchar(250) DEFAULT NULL,
  `tempo` varchar(250) DEFAULT NULL,
  `tempo_logado` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `audio`
--

CREATE TABLE IF NOT EXISTS `audio` (
  `audio_id` int(11) NOT NULL,
  `audio_nome` varchar(100) DEFAULT NULL,
  `audio_arquivo` varchar(255) DEFAULT NULL,
  `audio_status` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha`
--

CREATE TABLE IF NOT EXISTS `campanha` (
  `campanha_id` int(11) NOT NULL,
  `campanha_tipo` char(1) NOT NULL COMMENT 'Torpedo = T Discador = D',
  `campanha_nome` varchar(100) DEFAULT NULL,
  `campanha_data_inicio` datetime DEFAULT NULL,
  `campanha_data_fim` datetime DEFAULT NULL,
  `campanha_rota_fixo` varchar(50) NOT NULL,
  `campanha_rota_movel` varchar(50) NOT NULL,
  `campanha_rota_internacional` varchar(50) NOT NULL,
  `campanha_audio_1` varchar(100) DEFAULT NULL,
  `campanha_audio_2` varchar(100) DEFAULT NULL,
  `campanha_limite_chamada` varchar(7) DEFAULT NULL,
  `campanha_tts_1` text,
  `campanha_tts_2` text,
  `campanha_asr` text,
  `campanha_destino_tipo` varchar(200) DEFAULT NULL,
  `campanha_destino_complemento` varchar(200) NOT NULL,
  `campanha_agenda` varchar(200) DEFAULT NULL,
  `campanha_amd` tinyint(1) NOT NULL,
  `campanha_status` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha_agenda`
--

CREATE TABLE IF NOT EXISTS `campanha_agenda` (
  `campanha_id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha_sms`
--

CREATE TABLE IF NOT EXISTS `campanha_sms` (
  `campanha_sms_id` int(11) NOT NULL,
  `campanha_sms_tipo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Sms = S',
  `campanha_sms_nome` varchar(100) DEFAULT NULL,
  `campanha_sms_data_inicio` datetime DEFAULT NULL,
  `campanha_sms_agenda` varchar(200) DEFAULT NULL,
  `campanha_sms_status` char(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr`
--

CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL,
  `calldate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `tipo` varchar(50) NOT NULL,
  `tronco` varchar(100) DEFAULT NULL,
  `dcontext` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `lastapp` varchar(80) NOT NULL DEFAULT '',
  `lastdata` varchar(80) NOT NULL DEFAULT '',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `amaflags` int(11) NOT NULL DEFAULT '0',
  `accountcode` varchar(20) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `userfield` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr_regiao`
--

CREATE TABLE IF NOT EXISTS `cdr_regiao` (
  `id` int(10) unsigned NOT NULL,
  `calldate` date DEFAULT NULL,
  `toNorte` int(11) DEFAULT NULL,
  `toNordeste` int(11) DEFAULT NULL,
  `toCentroOeste` int(11) DEFAULT NULL,
  `toSudeste` int(11) DEFAULT NULL,
  `toSul` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr_sms`
--

CREATE TABLE IF NOT EXISTS `cdr_sms` (
  `sms_id` int(11) NOT NULL,
  `sms_date` datetime NOT NULL,
  `sms_date_atualizacao` datetime DEFAULT NULL,
  `sms_campanha` varchar(250) NOT NULL,
  `sms_operadora` varchar(250) DEFAULT NULL,
  `sms_numero` varchar(15) NOT NULL,
  `sms_msg` varchar(250) NOT NULL,
  `sms_status` varchar(50) NOT NULL,
  `sms_lote` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr_tempo`
--

CREATE TABLE IF NOT EXISTS `cdr_tempo` (
  `id` int(10) unsigned NOT NULL,
  `calldate` date DEFAULT NULL,
  `callparametro` char(2) DEFAULT NULL COMMENT '08:00 as 09:59 A=1 N=11 10:00 as 11:59 A=2 N=12 12:00 as 13:59 A=3 N=13 14:00 as 15:59 A=4 N=14 16:00 as 17:59 A=5 N=15 18:00 as 19:59 A=6 N=16 20:00 as 23:00 A=7 N=17',
  `calltotal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `did`
--

CREATE TABLE IF NOT EXISTS `did` (
  `did_id` int(11) NOT NULL,
  `did_nome` varchar(50) DEFAULT NULL,
  `did_origem` varchar(100) DEFAULT NULL,
  `did_destino_func` varchar(50) NOT NULL,
  `did_destino` varchar(50) DEFAULT NULL,
  `did_hora_ss_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de segunda a sexta inicio',
  `did_hora_ss_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de segunda a sexta final',
  `did_arquivo_ss` varchar(50) DEFAULT NULL,
  `did_hora_s_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de sabado inicio',
  `did_hora_s_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de sabado fim',
  `did_arquivo_s` varchar(50) DEFAULT NULL,
  `did_hora_d_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de domingo inicio',
  `did_hora_d_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponíl de domingo fim',
  `did_arquivo_d` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `kl_users`
--

CREATE TABLE IF NOT EXISTS `kl_users` (
  `user_id` int(11) NOT NULL,
  `user_nome` varchar(200) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_ramal` varchar(4) DEFAULT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_senha` varchar(250) NOT NULL,
  `user_nivel` char(1) DEFAULT '1',
  `user_status` char(1) DEFAULT 'S' COMMENT ' S = ativo e N = inativo',
  `user_registrado` timestamp NULL DEFAULT NULL,
  `user_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `kl_users`
--

INSERT INTO `kl_users` (`user_id`, `user_nome`, `user_email`, `user_ramal`, `user_login`, `user_senha`, `user_nivel`, `user_status`, `user_registrado`, `user_atualizacao`) VALUES
(1, 'BrazisTelecom Suporte', 'suporte@brazistelecom.com.br', '0000', 'suporte', 'ee0afca3289b28e4f20e3a7afbc059d2', '3', 'S', '2016-10-10 19:04:04', '2018-05-17 16:11:20'),
(2, 'Usuario', 'user@user.com.br', '0000', 'usuario', 'f8032d5cae3de20fcec887f395ec9a6a', '1', 'S', '2016-10-14 17:20:23', '2018-05-17 20:22:18'),
(3, 'Administrador', 'admin@admin.com.br', '0000', 'admin', '5adafa5ec4a63a7c05e53ac1780b6bda', '3', 'S', '2016-10-20 17:08:13', '2018-06-19 11:37:34');

-- --------------------------------------------------------

--
-- Estrutura da tabela `numero`
--

CREATE TABLE IF NOT EXISTS `numero` (
  `numero_id` int(11) NOT NULL,
  `numero_fone` varchar(20) DEFAULT NULL,
  `numero_nome` varchar(200) DEFAULT NULL,
  `numero_cpf_cnpj` varchar(15) DEFAULT NULL,
  `numero_status` char(2) DEFAULT NULL,
  `agenda_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `numero_sms`
--

CREATE TABLE IF NOT EXISTS `numero_sms` (
  `numero_sms_id` int(11) NOT NULL,
  `numero_sms_fone` varchar(20) DEFAULT NULL,
  `numero_sms_msg` text,
  `numero_sms_status` char(2) DEFAULT NULL,
  `agenda_sms_id` int(10) DEFAULT NULL,
  `numero_sms_lote` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `queues`
--

CREATE TABLE IF NOT EXISTS `queues` (
  `queue_id` int(11) NOT NULL,
  `queue_name` varchar(200) DEFAULT NULL,
  `queue_strategy` varchar(200) DEFAULT NULL,
  `queue_ringinuse` char(1) DEFAULT NULL,
  `queue_timeout` int(11) DEFAULT NULL,
  `queue_announce_frequency` int(11) DEFAULT NULL,
  `queue_retry` int(11) DEFAULT NULL,
  `queue_wrapuptime` int(11) DEFAULT NULL,
  `queue_maxlen` int(11) DEFAULT NULL,
  `queue_weight` int(11) DEFAULT NULL,
  `queue_tipo` char(1) NOT NULL COMMENT 'R = Tipo Ramal e A = Tipo Agents',
  `queue_ramal` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `queues_fila`
--

CREATE TABLE IF NOT EXISTS `queues_fila` (
  `id` int(11) NOT NULL,
  `fila` varchar(250) NOT NULL,
  `numero` varchar(250) NOT NULL,
  `tempo` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ramaliax`
--

CREATE TABLE IF NOT EXISTS `ramaliax` (
  `iax_id` int(10) unsigned NOT NULL,
  `iax_numero` varchar(4) DEFAULT NULL,
  `iax_senha` varchar(12) DEFAULT NULL,
  `iax_callerid` varchar(15) DEFAULT NULL COMMENT 'numero ramal',
  `iax_codec1` varchar(10) DEFAULT NULL,
  `iax_codec2` varchar(10) DEFAULT NULL,
  `iax_codec3` varchar(10) DEFAULT NULL,
  `iax_host` varchar(15) DEFAULT NULL COMMENT 'dynamic ou manual',
  `iax_trunk` char(3) DEFAULT NULL COMMENT 'yes ou no'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ramalsip`
--

CREATE TABLE IF NOT EXISTS `ramalsip` (
  `sip_id` int(10) NOT NULL,
  `sip_numero` varchar(4) DEFAULT NULL,
  `sip_senha` varchar(12) CHARACTER SET latin1 DEFAULT NULL,
  `sip_callerid` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `sip_host` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `sip_dtmf_mold` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `sip_directmedia` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `sip_nat` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `sip_codec1` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `sip_codec2` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `sip_codec3` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `sip_qualifily` char(3) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=ucs2;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rest_sms`
--

CREATE TABLE IF NOT EXISTS `rest_sms` (
  `id` int(11) NOT NULL,
  `sms_cus_id` int(10) unsigned DEFAULT NULL,
  `sms_acc_id` int(10) unsigned DEFAULT NULL,
  `origem` varchar(13) DEFAULT NULL,
  `resposta` varchar(250) DEFAULT NULL,
  `data_recebimento` datetime DEFAULT NULL,
  `acao` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rotas`
--

CREATE TABLE IF NOT EXISTS `rotas` (
  `rota_id` int(11) NOT NULL,
  `rota_tronco_fixo_m` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_fixo_m` varchar(10) DEFAULT NULL,
  `rota_tronco_movel_m` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_movel_m` varchar(10) DEFAULT NULL,
  `rota_tronco_inter_m` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_inter_m` varchar(10) DEFAULT NULL,
  `rota_tronco_fixo_b1` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_fixo_b1` varchar(10) DEFAULT NULL,
  `rota_tronco_movel_b1` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_movel_b1` varchar(10) DEFAULT NULL,
  `rota_tronco_inter_b1` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_inter_b1` varchar(10) DEFAULT NULL,
  `rota_tronco_fixo_b2` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_fixo_b2` varchar(10) DEFAULT NULL,
  `rota_tronco_movel_b2` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_movel_b2` varchar(10) DEFAULT NULL,
  `rota_tronco_inter_b2` varchar(50) DEFAULT NULL,
  `rota_tronco_tipo_inter_b2` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `socket`
--

CREATE TABLE IF NOT EXISTS `socket` (
  `sock_id` int(11) NOT NULL,
  `sock_resource_id` varchar(40) DEFAULT NULL,
  `sock_user` varchar(100) DEFAULT NULL,
  `last_number` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tronco`
--

CREATE TABLE IF NOT EXISTS `tronco` (
  `tronco_id` int(11) NOT NULL,
  `tronco_tipo` varchar(5) DEFAULT NULL COMMENT 'iax ou sip...',
  `tronco_nome` varchar(50) DEFAULT NULL,
  `tronco_username` varchar(50) DEFAULT NULL,
  `tronco_senha` varchar(100) DEFAULT NULL,
  `tronco_callerid` varchar(100) DEFAULT NULL,
  `tronco_codec1` varchar(50) DEFAULT NULL,
  `tronco_codec2` varchar(50) DEFAULT NULL,
  `tronco_codec3` varchar(50) DEFAULT NULL,
  `tronco_host` varchar(15) DEFAULT NULL,
  `tronco_trunk` char(3) DEFAULT NULL,
  `tronco_fromuser` varchar(100) DEFAULT NULL,
  `tronco_fromdomain` varchar(100) DEFAULT NULL,
  `tronco_port` char(4) NOT NULL,
  `tronco_remover_prefixo` varchar(5) DEFAULT NULL,
  `tronco_add_prefixo` varchar(10) DEFAULT NULL,
  `tronco_dtmf_mold` varchar(100) DEFAULT NULL,
  `tronco_directmedia` char(3) DEFAULT NULL,
  `tronco_nat` varchar(100) DEFAULT NULL,
  `tronco_insecure` varchar(100) DEFAULT NULL,
  `tronco_register` varchar(200) DEFAULT NULL,
  `tronco_qualify` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation`
--
ALTER TABLE `activation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activecalls`
--
ALTER TABLE `activecalls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `canal` (`canal`);

--
-- Indexes for table `activecalls_torpedo`
--
ALTER TABLE `activecalls_torpedo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`agenda_id`);

--
-- Indexes for table `agenda_sms`
--
ALTER TABLE `agenda_sms`
  ADD PRIMARY KEY (`agenda_sms_id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`agent_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `agents_pause`
--
ALTER TABLE `agents_pause`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents_status`
--
ALTER TABLE `agents_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audio`
--
ALTER TABLE `audio`
  ADD PRIMARY KEY (`audio_id`);

--
-- Indexes for table `campanha`
--
ALTER TABLE `campanha`
  ADD PRIMARY KEY (`campanha_id`);

--
-- Indexes for table `campanha_agenda`
--
ALTER TABLE `campanha_agenda`
  ADD PRIMARY KEY (`campanha_id`,`agenda_id`),
  ADD KEY `agenda_id` (`agenda_id`);

--
-- Indexes for table `campanha_sms`
--
ALTER TABLE `campanha_sms`
  ADD PRIMARY KEY (`campanha_sms_id`);

--
-- Indexes for table `cdr`
--
ALTER TABLE `cdr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calldate` (`calldate`),
  ADD KEY `src` (`src`),
  ADD KEY `dst` (`dst`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `billsec` (`billsec`),
  ADD KEY `disposition` (`disposition`),
  ADD KEY `userfield` (`userfield`);

--
-- Indexes for table `cdr_regiao`
--
ALTER TABLE `cdr_regiao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cdr_sms`
--
ALTER TABLE `cdr_sms`
  ADD PRIMARY KEY (`sms_id`);

--
-- Indexes for table `cdr_tempo`
--
ALTER TABLE `cdr_tempo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `did`
--
ALTER TABLE `did`
  ADD PRIMARY KEY (`did_id`);

--
-- Indexes for table `kl_users`
--
ALTER TABLE `kl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `adm_email` (`user_email`);

--
-- Indexes for table `numero`
--
ALTER TABLE `numero`
  ADD PRIMARY KEY (`numero_id`),
  ADD KEY `agenda_id` (`agenda_id`);

--
-- Indexes for table `numero_sms`
--
ALTER TABLE `numero_sms`
  ADD PRIMARY KEY (`numero_sms_id`);

--
-- Indexes for table `queues`
--
ALTER TABLE `queues`
  ADD PRIMARY KEY (`queue_id`);

--
-- Indexes for table `queues_fila`
--
ALTER TABLE `queues_fila`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ramaliax`
--
ALTER TABLE `ramaliax`
  ADD PRIMARY KEY (`iax_id`);

--
-- Indexes for table `ramalsip`
--
ALTER TABLE `ramalsip`
  ADD PRIMARY KEY (`sip_id`);

--
-- Indexes for table `rest_sms`
--
ALTER TABLE `rest_sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rotas`
--
ALTER TABLE `rotas`
  ADD PRIMARY KEY (`rota_id`);

--
-- Indexes for table `socket`
--
ALTER TABLE `socket`
  ADD PRIMARY KEY (`sock_id`),
  ADD UNIQUE KEY `sock_resource_id` (`sock_resource_id`);

--
-- Indexes for table `tronco`
--
ALTER TABLE `tronco`
  ADD PRIMARY KEY (`tronco_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activation`
--
ALTER TABLE `activation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `activecalls`
--
ALTER TABLE `activecalls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activecalls_torpedo`
--
ALTER TABLE `activecalls_torpedo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `agenda_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agenda_sms`
--
ALTER TABLE `agenda_sms`
  MODIFY `agenda_sms_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agents_pause`
--
ALTER TABLE `agents_pause`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `agents_status`
--
ALTER TABLE `agents_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `audio`
--
ALTER TABLE `audio`
  MODIFY `audio_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campanha`
--
ALTER TABLE `campanha`
  MODIFY `campanha_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `campanha_sms`
--
ALTER TABLE `campanha_sms`
  MODIFY `campanha_sms_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cdr`
--
ALTER TABLE `cdr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cdr_regiao`
--
ALTER TABLE `cdr_regiao`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cdr_tempo`
--
ALTER TABLE `cdr_tempo`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `did`
--
ALTER TABLE `did`
  MODIFY `did_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kl_users`
--
ALTER TABLE `kl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `numero`
--
ALTER TABLE `numero`
  MODIFY `numero_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `numero_sms`
--
ALTER TABLE `numero_sms`
  MODIFY `numero_sms_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queues`
--
ALTER TABLE `queues`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `queues_fila`
--
ALTER TABLE `queues_fila`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ramaliax`
--
ALTER TABLE `ramaliax`
  MODIFY `iax_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ramalsip`
--
ALTER TABLE `ramalsip`
  MODIFY `sip_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rest_sms`
--
ALTER TABLE `rest_sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rotas`
--
ALTER TABLE `rotas`
  MODIFY `rota_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `socket`
--
ALTER TABLE `socket`
  MODIFY `sock_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tronco`
--
ALTER TABLE `tronco`
  MODIFY `tronco_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `numero`
--
ALTER TABLE `numero`
  ADD CONSTRAINT `numero_ibfk_1` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`agenda_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
