-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 07-Out-2019 às 23:48
-- Versão do servidor: 5.1.73-log
-- versão do PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `probilling`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `activecalls`
--

CREATE TABLE IF NOT EXISTS `activecalls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `canal` varchar(80) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `tronco` varchar(50) DEFAULT NULL,
  `ndiscado` varchar(25) DEFAULT '0',
  `codec` varchar(5) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` varchar(16) NOT NULL,
  `duration` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `clid` varchar(250) NOT NULL,
  `src` varchar(20) NOT NULL,
  `dst` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `canal` (`canal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `activecalls_torpedo`
--

CREATE TABLE IF NOT EXISTS `activecalls_torpedo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ramal` varchar(20) DEFAULT NULL,
  `nomedocliente` varchar(50) DEFAULT NULL,
  `cpf_cnpj` varchar(14) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `duracao` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `agenda_id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_nome` varchar(100) DEFAULT NULL,
  `agenda_descricao` text,
  `agenda_status` char(2) DEFAULT NULL,
  PRIMARY KEY (`agenda_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agenda_sms`
--

CREATE TABLE IF NOT EXISTS `agenda_sms` (
  `agenda_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `agenda_sms_nome` varchar(200) DEFAULT NULL,
  `agenda_sms_status` char(2) DEFAULT NULL,
  PRIMARY KEY (`agenda_sms_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents`
--

CREATE TABLE IF NOT EXISTS `agents` (
  `agent_id` int(11) NOT NULL AUTO_INCREMENT,
  `agent_user` varchar(50) NOT NULL,
  `agent_name` varchar(100) NOT NULL,
  `agent_pass` varchar(100) NOT NULL,
  `agent_pause` varchar(250) DEFAULT NULL,
  `agent_pause_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`agent_id`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents_pause`
--

CREATE TABLE IF NOT EXISTS `agents_pause` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agents_status`
--

CREATE TABLE IF NOT EXISTS `agents_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agente` varchar(250) NOT NULL,
  `ramal` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `channel` varchar(250) DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `codigo` varchar(250) DEFAULT NULL,
  `fila` varchar(250) DEFAULT NULL,
  `tempo` varchar(250) DEFAULT NULL,
  `tempo_logado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `audio`
--

CREATE TABLE IF NOT EXISTS `audio` (
  `audio_id` int(11) NOT NULL AUTO_INCREMENT,
  `audio_nome` varchar(100) DEFAULT NULL,
  `audio_arquivo` varchar(255) DEFAULT NULL,
  `audio_status` char(1) DEFAULT NULL,
  PRIMARY KEY (`audio_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha`
--

CREATE TABLE IF NOT EXISTS `campanha` (
  `campanha_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `campanha_status` char(2) DEFAULT NULL,
  PRIMARY KEY (`campanha_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha_agenda`
--

CREATE TABLE IF NOT EXISTS `campanha_agenda` (
  `campanha_id` int(11) NOT NULL,
  `agenda_id` int(11) NOT NULL,
  PRIMARY KEY (`campanha_id`,`agenda_id`),
  KEY `agenda_id` (`agenda_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `campanha_sms`
--

CREATE TABLE IF NOT EXISTS `campanha_sms` (
  `campanha_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `campanha_sms_tipo` char(1) NOT NULL DEFAULT 'S' COMMENT 'Sms = S',
  `campanha_sms_nome` varchar(100) DEFAULT NULL,
  `campanha_sms_data_inicio` datetime DEFAULT NULL,
  `campanha_sms_agenda` varchar(200) DEFAULT NULL,
  `campanha_sms_status` char(2) DEFAULT NULL,
  PRIMARY KEY (`campanha_sms_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr`
--

CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `userfield` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `calldate` (`calldate`),
  KEY `src` (`src`),
  KEY `dst` (`dst`),
  KEY `tipo` (`tipo`),
  KEY `billsec` (`billsec`),
  KEY `disposition` (`disposition`),
  KEY `userfield` (`userfield`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr_regiao`
--

CREATE TABLE IF NOT EXISTS `cdr_regiao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calldate` date DEFAULT NULL,
  `toNorte` int(11) DEFAULT NULL,
  `toNordeste` int(11) DEFAULT NULL,
  `toCentroOeste` int(11) DEFAULT NULL,
  `toSudeste` int(11) DEFAULT NULL,
  `toSul` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `sms_lote` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`sms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cdr_tempo`
--

CREATE TABLE IF NOT EXISTS `cdr_tempo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calldate` date DEFAULT NULL,
  `callparametro` char(2) DEFAULT NULL COMMENT '08:00 as 09:59 A=1 N=11 10:00 as 11:59 A=2 N=12 12:00 as 13:59 A=3 N=13 14:00 as 15:59 A=4 N=14 16:00 as 17:59 A=5 N=15 18:00 as 19:59 A=6 N=16 20:00 as 23:00 A=7 N=17',
  `calltotal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `did`
--

CREATE TABLE IF NOT EXISTS `did` (
  `did_id` int(11) NOT NULL AUTO_INCREMENT,
  `did_nome` varchar(50) DEFAULT NULL,
  `did_origem` varchar(100) DEFAULT NULL,
  `did_destino_func` varchar(50) NOT NULL,
  `did_destino` varchar(50) DEFAULT NULL,
  `did_hora_ss_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponível de segunda a sexta inicio',
  `did_hora_ss_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponível de segunda a sexta final',
  `did_arquivo_ss` varchar(50) DEFAULT NULL,
  `did_hora_s_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponível de sabado inicio',
  `did_hora_s_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponível de sabado fim',
  `did_arquivo_s` varchar(50) DEFAULT NULL,
  `did_hora_d_ini` varchar(10) DEFAULT NULL COMMENT 'hora disponível de domingo inicio',
  `did_hora_d_fim` varchar(10) DEFAULT NULL COMMENT 'hora disponível de domingo fim',
  `did_arquivo_d` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`did_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Estrutura da tabela `kl_users`
--

CREATE TABLE IF NOT EXISTS `kl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_nome` varchar(200) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_ramal` varchar(4) DEFAULT NULL,
  `user_login` varchar(30) NOT NULL,
  `user_senha` varchar(250) NOT NULL,
  `user_nivel` char(1) DEFAULT '1',
  `user_status` char(1) DEFAULT 'S' COMMENT ' S = ativo e N = inativo',
  `user_registrado` timestamp NULL DEFAULT NULL,
  `user_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `adm_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

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
  `numero_id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_fone` varchar(20) DEFAULT NULL,
  `numero_nome` varchar(200) DEFAULT NULL,
  `numero_cpf_cnpj` varchar(15) DEFAULT NULL,
  `numero_status` char(2) DEFAULT NULL,
  `agenda_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`numero_id`),
  KEY `agenda_id` (`agenda_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `numero_sms`
--

CREATE TABLE IF NOT EXISTS `numero_sms` (
  `numero_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_sms_fone` varchar(20) DEFAULT NULL,
  `numero_sms_msg` text,
  `numero_sms_status` char(2) DEFAULT NULL,
  `agenda_sms_id` int(10) DEFAULT NULL,
  `numero_sms_lote` varchar(250) NOT NULL,
  PRIMARY KEY (`numero_sms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `queues`
--

CREATE TABLE IF NOT EXISTS `queues` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `queue_ramal` text NOT NULL,
  PRIMARY KEY (`queue_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `queues_fila`
--

CREATE TABLE IF NOT EXISTS `queues_fila` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fila` varchar(250) NOT NULL,
  `numero` varchar(250) NOT NULL,
  `tempo` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ramaliax`
--

CREATE TABLE IF NOT EXISTS `ramaliax` (
  `iax_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iax_numero` varchar(4) DEFAULT NULL,
  `iax_senha` varchar(12) DEFAULT NULL,
  `iax_callerid` varchar(15) DEFAULT NULL COMMENT 'numero ramal',
  `iax_codec1` varchar(10) DEFAULT NULL,
  `iax_codec2` varchar(10) DEFAULT NULL,
  `iax_codec3` varchar(10) DEFAULT NULL,
  `iax_host` varchar(15) DEFAULT NULL COMMENT 'dynamic ou manual',
  `iax_trunk` char(3) DEFAULT NULL COMMENT 'yes ou no',
  PRIMARY KEY (`iax_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ramalsip`
--

CREATE TABLE IF NOT EXISTS `ramalsip` (
  `sip_id` int(10) NOT NULL AUTO_INCREMENT,
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
  `sip_qualifily` char(3) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`sip_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rest_sms`
--

CREATE TABLE IF NOT EXISTS `rest_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_cus_id` int(10) unsigned DEFAULT NULL,
  `sms_acc_id` int(10) unsigned DEFAULT NULL,
  `origem` varchar(13) DEFAULT NULL,
  `resposta` varchar(250) DEFAULT NULL,
  `data_recebimento` datetime DEFAULT NULL,
  `acao` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `rotas`
--

CREATE TABLE IF NOT EXISTS `rotas` (
  `rota_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `rota_tronco_tipo_inter_b2` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rota_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `socket`
--

CREATE TABLE IF NOT EXISTS `socket` (
  `sock_id` int(11) NOT NULL AUTO_INCREMENT,
  `sock_resource_id` varchar(40) DEFAULT NULL,
  `sock_user` varchar(100) DEFAULT NULL,
  `last_number` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`sock_id`),
  UNIQUE KEY `sock_resource_id` (`sock_resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tronco`
--

CREATE TABLE IF NOT EXISTS `tronco` (
  `tronco_id` int(11) NOT NULL AUTO_INCREMENT,
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
  `tronco_qualify` char(3) DEFAULT NULL,
  PRIMARY KEY (`tronco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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
