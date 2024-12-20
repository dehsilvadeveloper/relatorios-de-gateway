<?php

namespace App\Console\Commands;

use App\Domain\GatewayLog\DataTransferObjects\CreateGatewayLogDto;
use App\Domain\GatewayLog\Repositories\GatewayLogRepositoryInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportGatewayLogsCommand extends Command
{
	/**
     * Usage example:
     *
     * php artisan import:gateway-log
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:gateway-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa dados de logs de Gateway de arquivo presente no storage local';

    public function __construct(protected GatewayLogRepositoryInterface $gatewayLogRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
    	$filePath = 'gateway_logs/logs.txt';

    	if (!$this->fileExists($filePath)) {
	        $this->error('Arquivo não encontrado: '. $filePath);
	        return Command::FAILURE;
	    }

	    $this->info('Iniciando a importação dos dados...');

	    DB::beginTransaction();

	    try {
	        $this->processFile($filePath);

	        DB::commit();

	        $this->info('Importação concluída com sucesso!');

	        return Command::SUCCESS;
	    } catch (Throwable $exception) {
	        DB::rollBack();

	        $this->error('Erro durante a importação: ' . $exception->getMessage());
	        $this->error('Todas as alterações foram desfeitas.');

	        return Command::FAILURE;
	    }
    }

    private function fileExists(string $filePath): bool
	{
	    return Storage::disk('local')->exists($filePath);
	}

	private function processFile(string $filePath): void
	{
	    $stream = $this->getFileStream($filePath);
	    $lineNumber = 0;

		try {
		    while (($line = fgets($stream)) !== false) {
		        $lineNumber++;

		        // Ignorando possíveis linhas vazias
		        if (trim($line) === '') {
		            continue;
		        }

		        $this->processLine($line, $lineNumber);
		    }

		    if (!feof($stream)) {
		        throw new Exception(
			        'Erro ao processar o arquivo '. $filePath . '. A leitura foi interrompida antes de alcançar o final. Verifique se o arquivo possui algum problema.'
			    );
		    }
		} finally {
        	fclose($stream);
    	}
	}

	private function getFileStream(string $filePath): mixed
	{
	    $stream = Storage::disk('local')->readStream($filePath);

	    if (!$stream) {
	        throw new Exception('Falha ao abrir o arquivo para leitura: ' . $filePath);
	    }

	    return $stream;
	}

	private function processLine(string $line, int $lineNumber): void
	{
	    $this->info('Importando registro da linha '. $lineNumber . '.');

	    $data = json_decode($line, true);

	    if (json_last_error() !== JSON_ERROR_NONE) {
	        throw new Exception(
	        	'Erro ao decodificar JSON na linha '. $lineNumber . ':'  . json_last_error_msg()
	        );
	    }

	    $this->saveLineOnDatabase($data);
	}

	private function saveLineOnDatabase(array $data): void
	{
		$this->gatewayLogRepository->create(
            CreateGatewayLogDto::from([
                'service_id' => $data['service']['id'],
                'service_name' => $data['service']['name'],
                'consumer_id' => $data['authenticated_entity']['consumer_id']['uuid'],
                'latency_proxy' => $data['latencies']['proxy'],
                'latency_gateway' => $data['latencies']['gateway'],
                'latency_request' => $data['latencies']['request'],
                'raw_log' => json_encode($data)
            ])
        );
	}
}
