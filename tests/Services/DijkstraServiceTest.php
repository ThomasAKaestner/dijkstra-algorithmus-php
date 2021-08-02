<?php

declare(strict_types=1);

namespace Tests\Services;


use DijkstraAlgo\Vertex;
use DijkstraAlgo\Path;
use DijkstraAlgo\Services\DijkstraService;
use PHPUnit\Framework\TestCase;
use Tests\Util\PHPUnitUtil;

final class DijkstraServiceTest extends TestCase
{
    use PHPUnitUtil;

    private DijkstraService $dijkstraService;

    public function testFirstExample(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $this->dijkstraService = new DijkstraService($vertexes, $paths);

        $resultVertexMatrix = $this->dijkstraService->getVertexTable('A');

        $expectedVertexMatrix = [
            'A' =>['A', 0, null],
            'B' =>['B', 3, 'D'],
            'C' =>['C', 7, 'E'],
            'D' =>['D', 1, 'A'],
            'E' =>['E', 2, 'D'],
        ];

        $this->assertEquals($expectedVertexMatrix, $resultVertexMatrix);
    }

    public function testSecondExample(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E', 'F'];

        $paths = [
            ['A', 'B', 1],
            ['A', 'C', 2],
            ['A', 'E', 3],
            ['B', 'D', 1],
            ['D', 'F', 2],
            ['E', 'C', 2],
        ];

        $this->dijkstraService = new DijkstraService($vertexes, $paths);

        $resultVertexMatrix = $this->dijkstraService->getVertexTable('A');

        $expectedVertexMatrix = [
            'A' =>['A', 0, null],
            'B' =>['B', 1, 'A'],
            'C' =>['C', 2, 'A'],
            'D' =>['D', 2, 'B'],
            'E' =>['E', 3, 'A'],
            'F' =>['F', 4, 'D'],
        ];

        $this->assertEquals($expectedVertexMatrix, $resultVertexMatrix);
    }

    public function testGetUnhandledNeighbours(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $this->dijkstraService = new DijkstraService($vertexes, $paths);

        $unhandledNeighbours = PHPUnitUtil::callMethod(
            $this->dijkstraService,
            'getUnhandledNeighbours',
            ['A']
        );

        $this->assertEquals(array_values(['D', 'B']), array_values($unhandledNeighbours));
    }

    public function testGetUnhandledNeighboursMixed(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['Z', 'A', 1],
            ['B', 'Z', 2],
            ['Z', 'C', 2],
        ];

        $dijkstraService = new DijkstraService($vertexes, $paths);

        $unhandledNeighbours = PHPUnitUtil::callMethod(
            $dijkstraService,
            'getUnhandledNeighbours',
            ['Z']
        );

        $this->assertEquals(array_values(['A', 'B', 'C']), array_values($unhandledNeighbours));
    }

    public function testCreateVertexMatrix(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];


        $dijkstraService = new DijkstraService($vertexes, $paths);

        PHPUnitUtil::callMethod(
            $dijkstraService,
            'createVertexMatrix',
            []
        );

        $expectation = [
            ['A', null, null],
            ['B', null, null],
            ['C', null, null],
            ['D', null, null],
            ['E', null, null],
        ];

        $this->assertEquals(
            array_values($expectation),
            array_values($dijkstraService->vertexMatrix)
        );
    }

    public function testGetPathByVertexKeys(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];


        $dijkstraService = new DijkstraService($vertexes, $paths);

        $path = PHPUnitUtil::callMethod(
            $dijkstraService,
            'getPathByVertexKeys',
            ['A', 'B']
        );

        $this->assertEquals(['A', 'B', 6], $path);
    }

    public function testInstantiateStartingPoint(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $dijkstraService = new DijkstraService($vertexes, $paths);

        PHPUnitUtil::callMethod(
            $dijkstraService,
            'createVertexMatrix',
            []
        );

        PHPUnitUtil::callMethod(
            $dijkstraService,
            'instantiateStartingPoint',
            ['A']
        );

        $expectation = [
            'A' => ['A', 0, null],
            'B' => ['B', null, null],
            'C' => ['C', null, null],
            'D' => ['D', null, null],
            'E' => ['E', null, null],
        ];

        $this->assertEquals($expectation, $dijkstraService->vertexMatrix);
    }

    public function testHandleNeighbour(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $dijkstraService = new DijkstraService($vertexes, $paths);

        $dijkstraService->vertexMatrix = [
            'A' =>['A', 0, null],
            'B' =>['B', null, null],
            'C' =>['C', null, null],
            'D' =>['D', null, null],
            'E' =>['E', null, null],
        ];
        PHPUnitUtil::callMethod(
            $dijkstraService,
            'handleNeighbour',
            ['A', 'B']
        );

        $expectedVertexMatrix = [
            'A' => ['A', 0, null],
            'B' => ['B', 6, 'A'],
            'C' => ['C', null, null],
            'D' => ['D', null, null],
            'E' => ['E', null, null],
        ];

        $this->assertEquals($expectedVertexMatrix, $dijkstraService->vertexMatrix);
    }

    public function testHandleComplexNeighbour(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $dijkstraService = new DijkstraService($vertexes, $paths);

        $dijkstraService->vertexMatrix = [
            'A' =>['A', 0, null],
            'B' =>['B', null, null],
            'C' =>['C', null, null],
            'D' =>['D', null, null],
            'E' =>['E', null, null],
        ];
        PHPUnitUtil::callMethod(
            $dijkstraService,
            'handleNeighbour',
            ['A', 'B']
        );

        $expectedVertexMatrix = [
            'A' => ['A', 0, null],
            'B' => ['B', 6, 'A'],
            'C' => ['C', null, null],
            'D' => ['D', null, null],
            'E' => ['E', null, null],
        ];

        $this->assertEquals($expectedVertexMatrix, $dijkstraService->vertexMatrix);
    }

    public function testGetSmallestFromBegin(): void
    {
        $vertexes = ['A', 'B', 'C', 'D', 'E'];

        $paths = [
            ['A', 'D', 1],
            ['D', 'E', 1],
            ['A', 'B', 6],
            ['D', 'B', 2],
            ['E', 'B', 2],
            ['E', 'C', 5],
            ['B', 'C', 5]
        ];

        $dijkstraService = new DijkstraService($vertexes, $paths);

        $dijkstraService->vertexMatrix = [
            'A' =>['A', 0, null],
            'B' =>['B', 3, 'D'],
            'C' =>['C', null, null],
            'D' =>['D', 1, 'A'],
            'E' =>['E', 2, 'D'],
        ];
        $dijkstraService->visited = ['A'];

        $smallest = PHPUnitUtil::callMethod(
            $dijkstraService,
            'getSmallestUnhandledFromBegin',
            []
        );
        $this->assertEquals('D', $smallest);
    }
}
