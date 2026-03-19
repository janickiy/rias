<?php

namespace App\Repositories;

use App\DTO\Admin\ProductParameterData;
use App\Models\ProductParameters;

class ProductParameterRepository
{
    public function create(ProductParameterData $data): ProductParameters
    {
        return ProductParameters::create($data->toArray());
    }

    public function update(ProductParameters $parameter, ProductParameterData $data): ProductParameters
    {
        $parameter->update($data->toArray());

        return $parameter->refresh();
    }

    public function delete(ProductParameters $parameter): void
    {
        $parameter->delete();
    }
}
