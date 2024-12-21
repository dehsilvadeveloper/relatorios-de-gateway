## Utilizando a applicação

Breve.

## Queries dos relatórios

As seguintes `SQL queries` foram utilizadas para obter os dados dos relatórios no banco de dados.

- Total de requisições por consumidor

```sql
SELECT consumer_id, COUNT(*) AS total_requests FROM gateway_logs GROUP BY consumer_id;
```

- Total de requisições por serviço

```sql
SELECT service_id, service_name, COUNT(*) AS total_requests FROM gateway_logs GROUP BY service_id, service_name;
```

- Tempo médio das latências (`request`, `proxy` e `gateway`) agrupadas por serviço

```sql
SELECT service_id, service_name, AVG(latency_proxy) AS avg_time_latency_proxy, AVG(latency_gateway) AS avg_time_latency_gateway, AVG(latency_request) AS avg_time_latency_request FROM gateway_logs GROUP BY service_id, service_name;
```