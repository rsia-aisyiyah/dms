<?php

namespace Tests\Unit;

use App\Services\KunjunganRalanServices;
use App\Models\RegPeriksa;
use Mockery;
use PHPUnit\Framework\TestCase;

class KunjunganRalanTest extends TestCase
{
    public function testGetDataKunjunganRajal()
    {
        // Mock the RegPeriksa model
        $regPeriksaMock = Mockery::mock(RegPeriksa::class);

        // Define the expected behavior of the model
        $regPeriksaMock->shouldReceive('whereMonth')
            ->with('tgl_registrasi', '10')
            ->andReturnSelf();

        $regPeriksaMock->shouldReceive('whereYear')
            ->with('tgl_registrasi', '2024')
            ->andReturnSelf();

        $regPeriksaMock->shouldReceive('where')
            ->with('status_lanjut', 'Ralan')
            ->andReturnSelf();

        $regPeriksaMock->shouldReceive('where')
            ->with('stts', '!=', 'Batal')
            ->andReturnSelf();

        $regPeriksaMock->shouldReceive('get')
            ->andReturn(collect(['sample_data']));

        // Inject the mock into the service
        $service = new KunjunganRalanServices($regPeriksaMock);

        // Call the method with test parameters
        $result = $service->getDataKunjunganRajal('10', '2024');

        var_dump($result);
        // Assert the expected outcome
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertEquals('sample_data', $result->first());
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
