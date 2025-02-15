# Livewire Auto CRUD

A Laravel package to generate **Livewire CRUD scaffolding** for your models, similar to Symfony's `make:crud`.

---

## Features

- Generates **Livewire components** for CRUD operations (Index, Create, Edit, Show).
- Automatically creates **Blade views** for each component.
- Supports dynamic form generation based on the model's fillable fields.
- Easy to use with a single Artisan command.

---

## Installation

1. **Install via Composer**:
   Run the following command in your Laravel project:
   ```bash
   composer require iamchris/livewire-autocrud

  
   ```
Generate CRUD scaffolding for a model:

   ```
    Publish the Package Configuration (if applicable):

   
    php artisan vendor:publish --provider="iamchris\LivewireAutoCrud\LivewireAutoCrudServiceProvider"

 
```
To generate CRUD scaffolding for a model, run:
```

php artisan make:livewire-crud Product

This will create the following:

    Livewire Components:

        Product/Index.php

        Product/Create.php

        Product/Edit.php

        Product/Show.php

    Blade Views:

        resources/views/livewire/product/index.blade.php

        resources/views/livewire/product/create.blade.php

        resources/views/livewire/product/edit.blade.php

        resources/views/livewire/product/show.blade.php
