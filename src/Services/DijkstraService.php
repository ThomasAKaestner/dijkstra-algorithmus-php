<?php

declare(strict_types=1);

namespace DijkstraAlgo\Services;

class DijkstraService
{
    public array $vertexes;
    public array $paths;
    public array $vertexMatrix;
    public array $visited = [];
    public array $unvisited = [];
    public string $startVertex;

    public function __construct(array $vertexes = [], array $paths = [])
    {
        $this->vertexes = $vertexes;
        $this->paths = $paths;
    }

    public function getVertexTable(string $startVertex): array
    {
        $this->startVertex = $startVertex;
        $this->unvisited = $this->vertexes;

        $this->createVertexMatrix();
        $this->instantiateStartingPoint($startVertex);

        $handledVertex = $startVertex;
        while ($this->unvisited != []) {

            $neighbours = $this->getUnhandledNeighbours($handledVertex);
            $lastNeighbour = null;
            foreach ($neighbours as $neighbour) {
                $this->handleNeighbour($handledVertex, $neighbour);
            }
            foreach($this->unvisited as $key =>  $unvisited) {
                if($unvisited === $handledVertex) {
                    unset($this->unvisited[$key]);
                }
            }

            if(!in_array($handledVertex, $this->visited)){
                $this->visited[] = $handledVertex;
            }
            $handledVertex = $this->getSmallestUnhandledFromBegin();
        }

        return $this->vertexMatrix;
    }

    private function createVertexMatrix(): void
    {
        foreach ($this->vertexes as $vertex) {
            $this->vertexMatrix[$vertex] = [$vertex, null, null];
        }
    }

    private function getUnhandledNeighbours(string $handledVertex): array
    {
        $temp = [];
        foreach ($this->paths as $key => $path) {
            if ($this->visited === $path) {
                continue;
            }
            if ($path[0] === $handledVertex) {
                $temp[$key] = $path[1];
            } else {
                if ($path[1] === $handledVertex) {
                    $temp[$key] = $path[0];
                }
            }
        }
        return $temp;
    }

    private function getPathByVertexKeys(string $vertexKey1, string $vertexKey2): array
    {
        foreach ($this->paths as $path) {
            if (($path[0] === $vertexKey1 && $path[1] === $vertexKey2) ||
                $path[1] === $vertexKey2 && $path[0] === $vertexKey1
            ) {
                return $path;
            }
        }

        return [];
    }

    private function instantiateStartingPoint(string $startingVertex): void
    {
        foreach ($this->vertexMatrix as $key => $row) {
            if ($row[1] === $startingVertex) {
                $this->vertexMatrix[$key] = [$startingVertex, 0, null];
            }
        }
    }

    private function handleNeighbour(string $handledVertex, string $neighbour): void
    {
        $path = $this->getPathByVertexKeys($handledVertex, $neighbour);
        if(isset($path[2]) &&(($this->vertexMatrix[$neighbour][1] === null && $this->vertexMatrix[$neighbour][2] === null) ||
             $this->vertexMatrix[$neighbour][1] > (int)$this->vertexMatrix[$handledVertex][1] + $path[2])
        ) {
            $this->vertexMatrix[$neighbour][1] = $this->vertexMatrix[$handledVertex][1] + $path[2];
            $this->vertexMatrix[$neighbour][2] = $handledVertex;
        }
    }

    private function getSmallestUnhandledFromBegin(): string
    {
        $array = [];
        foreach ($this->vertexMatrix as $key => $row) {
            if($row[1] !== null && !in_array($key, $this->visited)) {
                $array[$this->vertexMatrix[$key][1]] = $this->vertexMatrix[$key][0];
            }
        }
        return $array === [] ? '' : $array[min(array_keys($array))];
    }
}



