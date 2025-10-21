# Zeidex Assignment

A Laravel 12 application implementing prime number analysis and transaction reporting with advanced querying capabilities.

## Project Overview

### Task 1: Prime Number Analysis
Console command that generates prime numbers using the Sieve of Eratosthenes algorithm, calculates statistical metrics (count, gaps, execution time, memory usage), and stores results in JSON format for aggregation analysis.

### Task 2: Transaction Reporting
Advanced database querying system with multi-table joins, aggregations, filtering, and pagination. Includes caching for performance optimization and RESTful API with resources and collections.

---

## Quick Start with Docker

### Prerequisites
- Docker
- Docker Compose
- Make

### Setup
```bash
  make setup
```

Or manually if you want:
```bash
  docker-compose up -d
  docker-compose exec app composer install
  docker-compose exec app cp .env.docker .env
  docker-compose exec app php artisan key:generate
  docker-compose exec app php artisan migrate:fresh --seed
```

Access at: **http://localhost:8000**

## Usage Examples

### Task 1: Prime Analysis
```bash
  docker-compose exec app php artisan primes:analyze 100000
```

**Output:**
```
Analyzing primes up to 100000

Total Primes: 9592
Average Gap: 10.43
Max Gap: 114
Execution Time: 0.0234s
Memory Usage: 1.15MB
Estimated Time Complexity: O(n log log n)

Results saved to 1729512345_100000.json for example
```

**JSON Result** (stored in `storage/results/`):
```json
{
  "limit": 100000,
  "execution_time": 0.0234,
  "memory_usage": 1.15,
  "prime_count": 9592,
  "avg_gap": 10.43,
  "max_gap": 114,
  "complexity": "O(n log log n)"
}
```

**Example Screenshots:**
- See `prime-test.png` in project root
- See `aggregation.png` in project root

### Task 2: Transaction Report
```bash
curl http://localhost:8000/api/v1/transaction-report
curl http://localhost:8000/api/v1/transaction-report?per_page=5
curl http://localhost:8000/api/v1/transaction-report?date_from=2025-10-01&date_to=2025-10-31
```

**Output:**
```json
{
  "data": [
    {
      "user": "Younis Jad",
      "total_deposits": "12,200.00",
      "total_withdrawals": "2,000.00",
      "net_balance": "10,200.00"
    }
  ],
  "meta": {
    "total": 10,
    "per_page": 10,
    "current_page": 1,
    "last_page": 1
  }
}
```

---

## Algorithm & Design Choices

### Sieve of Eratosthenes
**Why:** Most efficient algorithm for finding all primes up to n. Eliminates multiples of each prime systematically.

**Alternatives Considered:**
- Trial Division: O(n√n) - too slow for large n
- Segmented Sieve: More complex, unnecessary for limits < 10^7

**Implementation:** Boolean array tracks primality, iterates up to √n marking multiples.

### Database Query Design
**Why:** Single query with JOINs and aggregations minimizes database round trips.

**Alternatives Considered:**
- Separate queries per user: N+1 problem
- Eloquent relationships only: Less efficient for aggregations

**Implementation:** Raw SQL with CASE statements for conditional sums, HAVING for filtering aggregated results.

### Caching Strategy
**Why:** Transaction reports rarely change, cache reduces database load.

**Implementation:** Key-based caching with 1-hour TTL based on query parameters.

---

## Time Complexity Analysis

### Task 1: Sieve of Eratosthenes

**Time Complexity: O(n log log n)**

The algorithm marks multiples of each prime up to √n:
- Outer loop: √n iterations
- Inner loop: n/p operations for prime p
- Sum: n × (1/2 + 1/3 + 1/5 + ...) ≈ n log log n

**Space Complexity: O(n)**
- Boolean array of size n

**Measured Performance:**
| Input | Primes | Time | Memory |
|-------|--------|------|--------|
| 100 | 25 | ~0.001s | 0.01MB |
| 10,000 | 1,229 | ~0.005s | 0.2MB |
| 100,000 | 9,592 | ~0.045s | 1.2MB |
| 1,000,000 | 78,498 | ~0.5s | 12MB |

### Task 2: Database Query

**Time Complexity: O(t + u log u)**

Where t = transactions, u = users:
- JOIN operations: O(u + a + t) where a = accounts
- GROUP BY: O(t)
- HAVING filter: O(u)
- ORDER BY: O(u log u)

**Space Complexity: O(u)**
- Result set contains one row per user

**Optimization:**
- Indexed foreign keys for fast joins
- Single query eliminates N+1 problem
- Caching reduces repeated queries to O(1)

---

## Testing
```bash
  make test
```

**Coverage:**
- Unit tests: Services, models, algorithms
- Feature tests: API endpoints, commands
- Integration tests: Database relationships

---
## Requirements Without Docker

- PHP 8.2+
- Laravel 12
- MySQL 8.0 or SQLite
- Redis (for caching)
- Composer

