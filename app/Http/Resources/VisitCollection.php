<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\VisitResource;

class VisitCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'rows' => VisitResource::collection($this->collection),
            'meta' => [
                'currentPage' => $this->currentPage(),
                'perPage' => $this->perPage(),
                'total' => $this->total(),
                'lastPage' => $this->lastPage(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'hasMorePages' => $this->hasMorePages(),
            ]
        ];
    }
}
