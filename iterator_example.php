<?php
/**
 * Iterator Design Pattern — PHP 8.3 example
 * Midterm Project: Introduction to Design Patterns in PHP (IPT10)
 *
 * Demonstrates the GoF Iterator pattern by letting a ProductCatalog
 * be traversed with foreach without exposing its internal storage.
 */

declare(strict_types=1);

final class Product
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
    ) {}
}

/**
 * ConcreteIterator: knows how to walk the catalog's internal array
 * without exposing that array to client code.
 */
final class ProductIterator implements Iterator
{
    private int $position = 0;

    public function __construct(private readonly array $items) {}

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}

/**
 * ConcreteAggregate: exposes traversal via getIterator() only.
 * Internal storage (an array today) could become a database
 * cursor tomorrow with zero change to client code.
 */
final class ProductCatalog implements IteratorAggregate
{
    /** @var Product[] */
    private array $products = [];

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getIterator(): Iterator
    {
        return new ProductIterator($this->products);
    }
}

// ---- Client code -----------------------------------------------------
$catalog = new ProductCatalog();
$catalog->addProduct(new Product('Mechanical Keyboard', 2499.00));
$catalog->addProduct(new Product('USB-C Hub', 899.50));
$catalog->addProduct(new Product('27" Monitor', 8990.00));

// The client only knows it can foreach() the catalog — it never
// touches the private $products array directly.
foreach ($catalog as $index => $product) {
    printf("[%d] %s - PHP %.2f\n", $index, $product->name, $product->price);
}
