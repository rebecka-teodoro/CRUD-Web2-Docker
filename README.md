# CRUD de contatos com PHP e Docker

Projeto acadêmico de desenvolvimento web com páginas PHP para cadastrar, listar, alterar e excluir contatos. O repositório também contém uma configuração Docker para PHP/Apache e MySQL.

## Tecnologias e estrutura

- PHP e MySQLi nas operações de banco de dados.
- HTML nas interfaces.
- Imagem PHP 8.2 com Apache no Dockerfile.
- MySQL 8.0 e Docker Compose na configuração do ambiente.

| Caminho | Conteúdo |
| --- | --- |
| `app/` | Interfaces e operações do cadastro |
| `app/conexao.php` | Conexão utilizada pelas páginas PHP |
| `db/init.sql` | Script inicial do banco |
| `Dockerfile` | Construção da imagem PHP/Apache |
| `docker-compose.yml` | Serviços da aplicação e MySQL |

## Estado atual

O código e a configuração Docker ainda precisam ser alinhados antes de uma demonstração funcional:

1. `app/conexao.php` usa `localhost`, banco `agenda` e MySQLi. O Compose define o serviço `db`, banco `crud_db` e variáveis de ambiente que a conexão ainda não lê.
2. As operações PHP consultam a tabela `contato`, mas `db/init.sql` cria a tabela `items`.
3. O Dockerfile instala `pdo_mysql`, mas o código usa `mysqli`.
4. O script SQL possui uma cláusula `ON DUPLICATE KEY UPDATE` separada do INSERT por um ponto e vírgula.
5. As consultas concatenam entradas do formulário. Antes de qualquer uso público, precisam de consultas parametrizadas, validação de entrada e escape na saída HTML.

## Ambiente local

Requisitos: Git, Docker Engine/Docker Desktop e Docker Compose.

```bash
git clone https://github.com/rebecka-teodoro/CRUD-Web2-Docker.git
cd CRUD-Web2-Docker
docker compose up --build -d
docker compose logs app db
```

Esses comandos iniciam a tentativa de construção dos serviços; **não garantem que o CRUD funcione**, devido às pendências descritas acima. A execução não foi validada em ambiente Docker nesta revisão.

O Compose publica a aplicação na porta 80 e o MySQL na 3306. Verifique conflitos com serviços locais. O script de inicialização é aplicado somente quando o diretório de dados do MySQL está vazio.

Para parar os serviços preservando o volume nomeado:

```bash
docker compose down
```

## Próximos passos

- Unificar conexão, credenciais de desenvolvimento e esquema do banco.
- Corrigir a inicialização SQL e habilitar a extensão utilizada.
- Implementar consultas parametrizadas, validação e tratamento de erros.
- Validar manualmente criação, listagem, edição e exclusão de contatos.
- Acrescentar capturas de tela após a validação.

Este é um projeto de estudo. As configurações de banco presentes no Compose são exemplos locais; não devem ser reutilizadas em produção.
