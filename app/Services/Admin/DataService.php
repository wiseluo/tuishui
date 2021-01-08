<?php

namespace App\Services\Admin;

use App\Repositories\DataRepository;

class DataService
{
    public function __construct(DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    public function getTypesByFatherId($id)
    {
        return $this->dataRepository->getTypesByFatherId($id);
    }

    public function getTypesNameByFatherId($id)
    {
        return $this->dataRepository->getTypesNameByFatherId($id);
    }
}
