## Dijkstra's short path algorithm in PHP

### Description:

Dijkstra's algorithm is an algorithm for finding the shortest paths between nodes in a graph, which may represent, for
example, road networks. It was conceived by computer scientist Edsger W. Dijkstra in 1956 and published three years
later.

### Implementation:

```php

class DijkstraInterface
{
    public array $vertexes;
    public array $paths;
    public array $vertexMatrix;
    public array $visited = [];
    public array $unvisited = [];
    public string $startVertex;

    /**
    * DijkstraInterface constructor.
    * The constructor takes an array of vertexes an their paths as parameters. 
    * @param array $vertexes
    * @param array $paths
    */
    public function __construct(array $vertexes = [], array $paths = []);

    /**
    * This creates the vertextable by the dijkstra algorithm. It takes a startvertex as a parameter 
    * and returns the whole vertextTable.  
    * @param string $startVertex
    * @return array
    */
    public function getVertexTable(string $startVertex): array;
    
    /**
    * This creates the initializes the vertexMatrix.
    */
    private function createVertexMatrix(): void;
    
    /**
    * @param string $handledVertex
    * @return array
    */
    private function getUnhandledNeighbours(string $handledVertex): array

    /**
    * @param string $vertexKey1
    * @param string $vertexKey2
    * @return array
    */
    private function getPathByVertexKeys(string $vertexKey1, string $vertexKey2): array

    /**
    * @param string $startingVertex
    */
    private function instantiateStartingPoint(string $startingVertex): void

    /**
    * @param string $handledVertex
    * @param string $neighbour
    */
    private function handleNeighbour(string $handledVertex, string $neighbour): void

    
    /**
    * Returns the vertex with the smallest distance from the $startVertex.
    * @return string
    */
    private function getSmallestUnhandledFromBegin(): string
}
```

### Example:

![Dijkstra Image PHP](img/dijkstra.png)

### Result:

Vertex| Shortest Distance from A|Previous Vertex
------|------|------
A|0|
B|3|D
C|7|E
D|1|A
E|2|D
