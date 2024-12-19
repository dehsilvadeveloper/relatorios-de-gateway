## Inicializando / encerrando a aplicação

### Inicializando a aplicação

Se você deseja **iniciar** a aplicação, use o comando a seguir num terminal aberto na pasta da aplicação:

```
docker-compose up -d
```

A opção `-d` significa que o terminal ficará "desacoplado" (*detached*), em outras palavras, não será necessáriomanter o terminal aberto para que a aplicação siga rodando normalmente.

### Encerrando a aplicação

Se você deseja **encerrar** a aplicação, use o comando a seguir num terminal aberto na pasta da aplicação:

```
docker-compose down
```

Este comando vai encerrar todos os containers Docker e a aplicação não estará mais disponível para uso.

Se você iniciou a aplicação sem o uso da opção `-d`, você deverá utilizar a seguinte combinação de teclas no terminal aberto para encerrá-la:

`ctrl + c`