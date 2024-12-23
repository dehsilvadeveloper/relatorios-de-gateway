<?php

namespace Tests\Unit\App\Http\Requests;

use App\Domain\Report\Enums\ReportTypeEnum;
use App\Http\Requests\CreateReportRequest;
use Database\Seeders\ReportTypeSeeder;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CreateReportRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ReportTypeSeeder::class);
    }

    /**
     * @group requests
     * @group report
     */
    public function test_pass_with_valid_request(): void
    {
        $data = [
            'report_type_id' => ReportTypeEnum::TOTAL_REQUESTS_BY_SERVICE->value
        ];

        $request = (new CreateReportRequest())->replace($data);
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertFalse($validator->fails());
    }

    /**
     * @group requests
     * @group report
     */
    public function test_fail_with_missing_required_fields(): void
    {
        $data = [];

        $request = (new CreateReportRequest())->replace($data);
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertTrue($validator->fails());
        $this->assertCount(1, $validator->errors());
    }

    /**
     * @group requests
     * @group report
     */
    public function test_fail_with_non_existent_report_type(): void
    {
        $data = [
            'report_type_id' => 9999
        ];

        $request = (new CreateReportRequest())->replace($data);
        $validator = Validator::make($data, $request->rules(), $request->messages());

        $this->assertTrue($validator->fails());
        $this->assertCount(1, $validator->errors());
        $this->assertTrue($validator->errors()->has('report_type_id'));
        $this->assertEquals(
            [
                'The selected report type id is invalid.'
            ],
            $validator->errors()->get('report_type_id')
        );
    }
}
