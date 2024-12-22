<?php

namespace Tests\Unit\App\Domain\Report\DataTransferObjects;

use App\Domain\Report\DataTransferObjects\UpdateReportDto;
use App\Domain\Report\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tests\DtoTestCase;

class UpdateReportDtoTest extends DtoTestCase
{
    /**
     * @group dtos
     * @group report
     */
    public function test_can_create_from_array_with_snakecase_keys(): void
    {
        $this->runCreationFromSnakecaseArrayAssertions(
            UpdateReportDto::class,
            $this->getValidData()
        );
    }

    /**
     * @group dtos
     * @group report
     */
    public function test_can_create_from_array_with_camelcase_keys(): void
    {
        $this->runCreationFromCamelcaseArrayAssertions(
            UpdateReportDto::class,
            $this->getValidData(useCamelCaseKeys: true)
        );
    }

    /**
     * @group dtos
     * @group report
     */
    public function test_cannot_create_from_array_with_invalid_values(): void
    {
        $this->runCreationFromArrayWithInvalidEnumValuesAssertions(
            UpdateReportDto::class,
            $this->getInvalidData()
        );
    }

    /**
     * @group dtos
     * @group report
     */
    public function test_can_create_from_request(): void
    {
        $this->runCreationFromRequestAssertions(
            UpdateReportDto::class,
            Request::create(
                '/dummy',
                'POST',
                $this->getValidData()
            )
        );
    }

    /**
     * @group dtos
     * @group report
     */
    public function test_cannot_create_from_request_with_invalid_values(): void
    {
        $this->runCreationFromRequestWithInvalidValuesAssertions(
            UpdateReportDto::class,
            Request::create(
                '/dummy',
                'POST',
                $this->getInvalidData()
            )
        );
    }

    private function getValidData(bool $useCamelCaseKeys = false): array
    {
        $validData = Report::factory()->make([
            'generated_at' => Carbon::now()
        ])->toArray();

        unset($validData['created_at']);
        unset($validData['updated_at']);

        return (!$useCamelCaseKeys) ? $validData : [
            'reportStatusId' => $validData['report_status_id'],
            'reportTypeId' => $validData['report_type_id'],
            'filename' => $validData['filename'],
            'generatedAt' => $validData['generated_at']
        ];
    }

    private function getInvalidData(): array
    {
        $invalidData = Report::factory()->make([
            'report_status_id' => 999999,
            'generated_at' => Carbon::now()
        ])->toArray();

        unset($invalidData['created_at']);
        unset($invalidData['updated_at']);

        return $invalidData;
    }
}
