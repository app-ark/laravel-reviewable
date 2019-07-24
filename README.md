# Laravel reviewable for Eloquent

## Installation

```bash
composer require app-ark/laravel-reviewable
```

## Usage

**Enable in you model**
```
<?php
use AppArk\Database\Eloquent\Reviewable;

class MyModel
{
    use Reviewable;
    // ...
}
```

**Static methods**
- MyModel::withRejected()
- MyModel::withoutRejected()
- MyModel::onlyRejected()
- MyModel::withReviewed()
- MyModel::withoutReviewed()
- MyModel::onlyReviewed()

**Dynamic methods**
- MyModel->review()
- MyModel->reject()

## Author
Xiaohui Lam

## LICENSE
MIT