## Instalação

### Requisitos da instalação

Para conseguir instalar e utilizar a aplicação, sua máquina precisa possuir o **Docker** instalado.

### Passo-a-passo da instalação

Estas são as instruções para instalar a aplicação, deixando ela preparada para uso. Abra um terminal na pasta onde você deseja salvar a aplicação e siga os passos a seguir.

1. Clone os arquivos do repositório para sua máquina.

```
git clone <url do repositorio> relatorios-de-gateway
```

2. Entre na pasta da aplicação.

```
cd relatorios-de-gateway
```

3. Duplique o arquivo `.env.example`, renomeando a cópia para `.env`. Este arquivo contém variáveis de ambiente que serão utilizadas pela aplicação. Você pode fazer isso manualmente ou utilizando o comando a seguir se você estiver utilizando o *Linux*:

```
cp .env.example .env
```

4. O próximo passo é efetuar o *build* dos containers **Docker**. Para isso você deve utilizar o comando a seguir:

```
docker-compose build --no-cache
```

Se você, por algum motivo, tiver a necessidade de efetuar o *build* de um container específico, você pode informar o nome do serviço na instrução do comando.

```
docker-compose build main --no-cache
```

Vale ressaltar que o nome do serviço é definido no arquivo **docker-compose.yml**.

5. Depois que os containers terminarem o processo de *build*, você precisa habilitá-los para uso. Para isso utilize o comando a seguir:

```
docker-compose up -d
```

A opção `-d` significa que o terminal ficará "desacoplado" (*detached*), em outras palavras, não será necessário manter o terminal aberto para que a aplicação siga rodando normalmente.

6. Agora você precisa instalar as dependências da aplicação utilizando o gerenciador de dependências chamado **Composer**. Para isso você deve usar o seguinte comando:

```
docker-compose exec main composer install --no-interaction
```

7. Depois disso você deve gerar um *autoload* otimizado das classes da aplicação (*classmap*) visando melhor performance. Para isto utilize o comando a seguir:

```
docker-compose exec main composer dump-autoload -o
```

8. O próximo passo é gerar a *chave da aplicação*. Esta chave é utilizada por padrão por qualquer encriptação realizada pela aplicação (senhas de usuário, por exemplo). Para isso você deve usar o comando a seguir:

```
docker-compose exec main php artisan key:generate
```

9. Agora você precisa executar as *migrations*, criando uma estrutura de *database* para sua aplicação. Para isso utilize o comando a seguir:

```
docker-compose exec main php artisan migrate
```

10. O próximo passo é executar as *seeders*, preenchendo as tabelas do *database* com os dados pertinentes. Para isso utilize o comando a seguir:

```
docker-compose exec main php artisan db:seed
```

Com isso todos os dados necessários para inicialização da aplicação estarão presentes no *database*.

Se você, por algum motivo, tiver a necessidade de executar uma classe *seeder* específica, você pode utilizar a opção **--class** informando o nome da classe desejada.

```
docker-compose exec main php artisan db:seed --class=GenericSeeder
```

**Instalação finalizada**

Depois de seguir os passos da instalação, a aplicação estará disponível para uso na seguinte url:

```
http://localhost:9998
```

A porta da aplicação pode ser customizada utilizando a variável de ambiente *APP_PORT_EXTERNAL*.

Você só precisa efetuar o procedimento de instalação uma única vez. A partir de agora bastará inicializar e encerrar a aplicação.