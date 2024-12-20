[![Laravel][laravel-shield]][ref-laravel]
[![PHP][php-shield]][ref-php]
[![MySQL][mysql-shield]][ref-mysql]
[![Composer][composer-shield]][ref-composer]
[![Docker][docker-shield]][ref-docker]
[![Git][git-shield]][ref-git]
[![Markdown][markdown-shield]][ref-markdown]
[![Env][env-shield]][ref-env]

# Desafio Relatório de Gateway

## Descrição do desafio

O desafio consiste em desenvolver uma aplicação que processe um **arquivo de logs** no formato TXT, gerado por um sistema de API Gateway. Cada entrada no log é um objeto JSON que contém informações detalhadas sobre requisições, incluindo dados sobre latências, serviços e consumidores. O objetivo do desafio é extrair informações relevantes e armazená-las em um banco de dados, além de gerar alguns relatórios com base nos dados importados.

### Sobre o arquivo de log

O arquivo **logs.txt** - armazenado na pasta `storage/app/gateway_logs` - contém informações de log geradas por um sistema de API Gateway. Cada solicitação foi registrada em um objeto JSON separado por uma nova linha `\n`, com o seguinte formato:

```
{
    "request": {
        "method": "GET",
        "uri": "/get",
        "url": "http://httpbin.org:8000/get",
        "size": "75",
        "querystring": {},
        "headers": {
            "accept": "*/*",
            "host": "httpbin.org",
            "user-agent": "curl/7.37.1"
        },
    },
    "upstream_uri": "/",
    "response": {
        "status": 200,
        "size": "434",
        "headers": {
            "Content-Length": "197",
            "via": "gateway/0.3.0",
            "Connection": "close",
            "access-control-allow-credentials": "true",
            "Content-Type": "application/json",
            "server": "nginx",
            "access-control-allow-origin": "*"
        }
    },
    "authenticated_entity": {
        "consumer_id": "80f74eef-31b8-45d5-c525-ae532297ea8e"
    },
    "route": {
        "created_at": 1521555129,
        "hosts": null,
        "id": "75818c5f-202d-4b82-a553-6a46e7c9a19e",
        "methods": ["GET","POST","PUT","DELETE","PATCH","OPTIONS","HEAD"],
        "paths": [
            "/example-path"
        ],
        "preserve_host": false,
        "protocols": [
            "http",
            "https"
        ],
        "regex_priority": 0,
        "service": {
            "id": "0590139e-7481-466c-bcdf-929adcaaf804"
        },
        "strip_path": true,
        "updated_at": 1521555129
    },
    "service": {
        "connect_timeout": 60000,
        "created_at": 1521554518,
        "host": "example.com",
        "id": "0590139e-7481-466c-bcdf-929adcaaf804",
        "name": "myservice",
        "path": "/",
        "port": 80,
        "protocol": "http",
        "read_timeout": 60000,
        "retries": 5,
        "updated_at": 1521554518,
        "write_timeout": 60000
    },
    "latencies": {
        "proxy": 1430,
        "gateway": 9,
        "request": 1921
    },
    "client_ip": "127.0.0.1",
    "started_at": 1433209822425
}
```

Como é possível visualizar no exemplo, a chave `latencies` contém alguns dados sobre as latências envolvidas:

- `proxy` é o tempo levado pelo serviço final para processar a requisição.
- `gateway` é a latência referente a execução de todos os plugins pelo gateway (API Gateway).
- `request` é o tempo decorrido entre o primeiro byte ser lido do cliente e o último byte ser enviado a ele. Útil para detectar clientes lentos.

### Requisitos

- **Processamento de Logs**: Implementar uma funcionalidade que leia o arquivo de log, extraia as informações necessárias e as armazene em um banco de dados.
- **Geração de Relatórios**: Criar relatórios em formato *CSV* com as seguintes informações:
    - Total de requisições por consumidor.
    - Total de requisições por serviço.
    - Tempo médio das latências (`request`, `proxy` e `gateway`) agrupadas por serviço.
- **Ambiente Docker**: Garantir que a solução seja entregue em um ambiente Docker, facilitando a configuração e execução do projeto.
- **Documentação**: Incluir documentação com instruções sobre como configurar o ambiente e rodar a solução.

### Avaliação

Atenda aos requisitos do desafio e dedique-se a fazer o melhor possível, utilizando as práticas que você considera mais adequadas para resolver o problema e demonstrar suas habilidades. Não se preocupe em entregar algo perfeito, o importante é mostrar seu raciocínio e abordagem para a solução.

## Documentação

* [Instalação](./docs/installation.md)
* [Inicializando / encerrando a aplicação](./docs/toggle_on_off_application.md)
* [Utilizando a aplicação](./docs/using_the_application.md)

## Desenvolvido com

| Nome       | Versão  |
| ---------- | -------- |
| Laravel | v10.x + |
| PHP | v8.2.x + |
| Docker | v20.10.x + |
| Docker Compose | v3.8.x + |
| MySQL | v8.0.x |

<!-- Badge Shields -->
[laravel-shield]: https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white
[php-shield]: https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white
[mysql-shield]: https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white
[composer-shield]: https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white
[docker-shield]: https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white
[git-shield]: https://img.shields.io/badge/git-%23F05033.svg?style=for-the-badge&logo=git&logoColor=white
[markdown-shield]: https://img.shields.io/badge/Markdown-000?style=for-the-badge&logo=markdown
[env-shield]: https://img.shields.io/badge/.ENV-ECD53F?logo=dotenv&logoColor=000&style=for-the-badge

<!-- References -->
[ref-laravel]: https://laravel.com/docs/10.x/readme
[ref-php]: https://www.php.net
[ref-mysql]: https://www.mysql.com
[ref-composer]: https://getcomposer.org
[ref-docker]: https://www.docker.com
[ref-git]: https://git-scm.com
[ref-markdown]: https://www.markdownguide.org/getting-started/
[ref-env]: https://medium.com/@sujathamudadla1213/what-is-the-use-of-env-8d6b3eb94843