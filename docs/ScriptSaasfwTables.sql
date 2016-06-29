use saasfw;

create table Usuario (
    id int(11) not null auto_increment primary key,
    nome varchar(50) not null,
    email varchar(100) not null unique,
    senha char(32) not null,
    imagem varchar(100),
    dataDeCadastro date not null,
    dataDeDesativacao date
);

insert into Usuario(id,nome,email,senha,imagem,dataDeCadastro,dataDeDesativacao) values(1,'sysadmin','sysadmin@admin.com.br',md5('sysadmin123'),null,'17.05.2016', null);

create table Menu(
    id int(11) not null auto_increment primary key,
    app varchar(50),
    acao varchar(50),
    label varchar(50) not null,
    ordem int(11) not null,
    img varchar(50),
    menuPrincipal int(11)
);

alter table Menu add constraint fk_menu_menuprincipal foreign key(menuPrincipal) references Menu(id) on update cascade on delete cascade;

/*Criação dos Menus do sistema*/
insert into Menu values(1, 'dashboard', 'usuario', 'Painel de Controle', 1, 'fa fa-dashboard', null);
insert into Menu values(2, null, null, 'Administração', 2, 'fa fa-desktop', null);
insert into Menu values(3, 'usuario', 'listar', 'Usuário', 3, null, 2);
insert into Menu values(4, 'configadmin', 'visualizar', 'Configurações', 4, null, 2);

insert into Menu values(10, null, null, 'Cadastros Auxiliares', 10, 'fa fa-edit', null);
insert into Menu values(11, 'planodeadesao', 'listar', 'Plano de Adesão', 11, null, 10);
insert into Menu values(12, 'politicadepreco', 'listar', 'Política de Preço', 12, null, 10);
insert into Menu values(13, 'modulo', 'listar', 'Módulos', 13, null, 10);

insert into Menu values(20, null, null, 'Cadastros Principais', 20, 'fa fa-file', null);
insert into Menu values(21, 'contratante', 'listar', 'Contratante', 21, null, 20);
insert into Menu values(22, 'contrato', 'listar', 'Gerenciar Contrato', 22, null, 20);

create table ConfiguracaoAdmin (
    id int(11) not null auto_increment primary key,
    diasParaEnvioDaCobrancaDePagamentosPendentes int(11) not null default 0,
    enviaEmailDeCobrancaParaPagamentosEmAtraso tinyint(1) not null default 0,
    finalizarContratosAutomaticamente tinyint(1) not null default 0,
    diasDeToleranciaParaPagamento int(11) not null default 0,
    formaPgtoGratis int(11),
    tipoPgtoGratis int(11)
);

create table Estado(
	id int(11) not null auto_increment primary key,
	uf char(2) not null unique,
	nome varchar(50) not null
);

create table Cidade(
	id int not null auto_increment primary key,
	codigo int(11) not null unique, 
	nome varchar(50) not null,
	estado int(11) not null
);

alter table Cidade add constraint fk_cidade_estado foreign key(estado) references Estado(id) on update cascade on delete cascade;

create table Pessoa(
	id int(11) not null auto_increment primary key,
	nome varchar(100) not null,
	documento varchar(14) not null unique,        
	logradouro varchar(100) not null,	
	numero varchar(20) not null,
	complemento varchar(20),
	bairro varchar(50) not null,
	cidade int(11) not null,
	estado int(11) not null,
	cep char(8) not null,
	email varchar(100) unique,    
	imagem varchar(100),    
	telefone1 varchar(11),
	telefone2 varchar(11)
);

alter table Pessoa add constraint fk_pessoa_estado foreign key(estado) references Estado(id) on update cascade;
alter table Pessoa add constraint fk_pessoa_cidade foreign key(cidade) references Cidade(id) on update cascade;

create table Contratante(
	id int(11) not null auto_increment primary key,
	pessoa int(11) not null,    
	dataDeCadastro date not null,
	alias varchar(32) not null unique,
    site varchar(50)
);

alter table Contratante add constraint fk_contratante_pessoa foreign key(pessoa) references Pessoa(id) on update cascade on delete cascade;

create table Cancelamento(
	id int(11) not null auto_increment primary key,
	data date not null,
	motivo text not null,
	responsavel int(11) not null
);

alter table Cancelamento add constraint fk_cancelamento_usuario foreign key(responsavel) references Usuario(id) on update cascade on delete cascade;

create table Modulo(
	id int(11) not null auto_increment primary key,
    identificador varchar(20) not null unique,
	descricao varchar(50) not null unique,
	cancelamento int(11)
);

alter table Modulo add constraint fk_modulo_cancelamento foreign key(cancelamento) references Cancelamento(id) on update cascade;

create table PlanoDeAdesao(
    id int(11) not null auto_increment primary key,
    descricao varchar(50) not null unique,
    duracao int(11) not null default 1,	
    gratis tinyint(1) not null default false,
    quantUsuario int(11) not null default 1,
    cancelamento int(11),
    check(duracao >= 0),
    check(quantUsuarios >= 0)
);

alter table PlanoDeAdesao add constraint fk_planodeadesao_cancelamento foreign key(cancelamento) references Cancelamento(id);

create table PlanoDeAdesaoModulo(
	planoDeAdesao int(11) not null,
	modulo int(11) not null,
	primary key(planoDeAdesao, modulo)
);

alter table PlanoDeAdesaoModulo add constraint fk_planodeadesamodulo_planodeadesao foreign key(planoDeAdesao) references PlanoDeAdesao(id) on update cascade on delete cascade;
alter table PlanoDeAdesaoModulo add constraint fk_planodeadesamodulo_modulo foreign key(modulo) references Modulo(id) on update cascade;

create table PoliticaDePreco(
	id int(11) not null auto_increment primary key,
	data date not null,
	valor numeric(10,2) not null default 0,
	planoDeAdesao int(11) not null
);

alter table PoliticaDePreco add constraint fk_politicadepreco_planodeadesao foreign key(planoDeAdesao) references PlanoDeAdesao(id) on update cascade;
alter table PoliticaDePreco add constraint unq_politicaDePreco unique(data, planoDeAdesao);

create table Contrato(
	id int(11) not null auto_increment primary key,
	codigo int(11) not null unique,
	dataDeAtivacao date,
	dataDeCriacao date not null,
	dataDeFinalizacao date,
	formaDePagamento int(11) not null,
	tipoDePagamento int(11) not null,
	cancelamento int(11),
	contratante int(11) not null,
	politicaDePreco int(11) not null
);

alter table Contrato add constraint fk_contrato_cancelamento foreign key(cancelamento) references Cancelamento(id) on update cascade;
alter table Contrato add constraint fk_contrato_contratante foreign key(contratante) references Contratante(id) on update cascade;
alter table Contrato add constraint fk_contrato_politicadepreco foreign key(politicaDePreco) references PoliticaDePreco(id) on update cascade;

create table Pagamento(
	id int(11) not null auto_increment primary key,
	dataDeCriacao date not null,
	dataDeVencimento date not null,    
	dataDePagamento date,
    dataDeConfirmacao date,
	valor numeric(10,2) not null default 0,
	cancelamento int(11),
    contrato int(11) not null
);

alter table Pagamento add constraint fk_pgto_contrato foreign key(contrato) references Contrato(id) on update cascade on delete cascade;
alter table Pagamento add constraint fk_pgto_cancelamento foreign key(cancelamento) references Cancelamento(id) on update cascade;

/********************TRIGGERS************************/

delimiter |
create trigger trg_gerar_codigo_contrato before insert on Contrato
for each row
begin
  set new.codigo = (select ifnull(max(codigo), 0) + 1 from Contrato);
end; |