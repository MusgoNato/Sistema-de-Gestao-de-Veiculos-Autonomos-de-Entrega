/* Criacao de tabelas, restricoes e relacionamentos */

CREATE TABLE usuario (
    id INT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(50) UNIQUE,
    data_nasc DATE,
    sexo CHAR(1),
    cpf CHAR(11) UNIQUE,
    ativo BOOLEAN,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE restaurante (
    id INT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    rua VARCHAR(100),
    bairro VARCHAR(100),
    numero VARCHAR(100),
    telefone VARCHAR(11),
    cnpj VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(50) UNIQUE,
    horario_funcionamento TIME
);

CREATE TABLE veiculo (
    id INT PRIMARY KEY,
    placa VARCHAR(8) NOT NULL UNIQUE,
    ano CHAR(4),
    modelo VARCHAR(50),
    status VARCHAR(50)
);

CREATE TABLE perfil (
    id INT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

CREATE TABLE alimento (
    id INT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    fk_restaurante_id INT NOT NULL
);

CREATE TABLE cofre (
    id INT PRIMARY KEY,
    codigo CHAR(8) NOT NULL,
    status BOOLEAN DEFAULT TRUE,
    fk_veiculo_id INT NOT NULL,
    UNIQUE (codigo, fk_veiculo_id)
);

CREATE TABLE pedido (
    id INT PRIMARY KEY,
    data DATE NOT NULL,
    status VARCHAR(50),
    valor_total DECIMAL(10,2) NOT NULL,
    pagamento VARCHAR(50),
    fk_usuario_id INT NOT NULL,
    fk_restaurante_id INT NOT NULL
);

CREATE TABLE entrega (
    id INT PRIMARY KEY,
    status VARCHAR(50),
    data_saida DATE,
    data_entrega DATE,
    hora_entrega TIME,
    fk_pedido_id INT NOT NULL,
    fk_viagem_id INT NOT NULL,
    fk_cofre_id INT
);

CREATE TABLE usuario_perfil_possui (
    data_atribuicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    fk_perfil_id INT NOT NULL,
    fk_usuario_id INT NOT NULL,
    PRIMARY KEY (fk_perfil_id, fk_usuario_id)
);

CREATE TABLE viagem (
    id INT PRIMARY KEY,
    data_saida DATE NOT NULL,
    hora_saida TIME,
    hora_retorno TIME,
    status VARCHAR(50),
    fk_veiculo_id INT NOT NULL
);

CREATE TABLE item_pedido_contem (
    id INT,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    fk_pedido_id INT NOT NULL,
    fk_alimento_id INT NOT NULL,
    PRIMARY KEY (id, fk_pedido_id, fk_alimento_id),
    UNIQUE (fk_pedido_id, fk_alimento_id)
);
 
ALTER TABLE alimento ADD CONSTRAINT FK_alimento_2
    FOREIGN KEY (fk_restaurante_id)
    REFERENCES restaurante (id)
    ON DELETE RESTRICT;
 
ALTER TABLE cofre ADD CONSTRAINT FK_cofre_2
    FOREIGN KEY (fk_veiculo_id)
    REFERENCES veiculo (id)
    ON DELETE RESTRICT;
 
ALTER TABLE pedido ADD CONSTRAINT FK_pedido_2
    FOREIGN KEY (fk_usuario_id)
    REFERENCES usuario (id)
    ON DELETE RESTRICT;
 
ALTER TABLE pedido ADD CONSTRAINT FK_pedido_3
    FOREIGN KEY (fk_restaurante_id)
    REFERENCES restaurante (id)
    ON DELETE RESTRICT;
 
ALTER TABLE entrega ADD CONSTRAINT FK_entrega_2
    FOREIGN KEY (fk_pedido_id)
    REFERENCES pedido (id)
    ON DELETE RESTRICT;
 
ALTER TABLE entrega ADD CONSTRAINT FK_entrega_3
    FOREIGN KEY (fk_viagem_id)
    REFERENCES viagem (id)
    ON DELETE RESTRICT;
 
ALTER TABLE entrega ADD CONSTRAINT FK_entrega_4
    FOREIGN KEY (fk_cofre_id)
    REFERENCES cofre (id)
    ON DELETE RESTRICT;
 
ALTER TABLE usuario_perfil_possui ADD CONSTRAINT FK_usuario_perfil_possui_1
    FOREIGN KEY (fk_perfil_id)
    REFERENCES perfil (id);
 
ALTER TABLE usuario_perfil_possui ADD CONSTRAINT FK_usuario_perfil_possui_2
    FOREIGN KEY (fk_usuario_id)
    REFERENCES usuario (id);
 
ALTER TABLE viagem ADD CONSTRAINT FK_viagem_2
    FOREIGN KEY (fk_veiculo_id)
    REFERENCES veiculo (id)
    ON DELETE RESTRICT;
 
ALTER TABLE item_pedido_contem ADD CONSTRAINT FK_item_pedido_contem_2
    FOREIGN KEY (fk_pedido_id)
    REFERENCES pedido (id);
 
ALTER TABLE item_pedido_contem ADD CONSTRAINT FK_item_pedido_contem_3
    FOREIGN KEY (fk_alimento_id)
    REFERENCES alimento (id);