<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
  public function toArray($request)
  {

    if ( count($this->collection ) === 0) :
      return [
        'rows' => [],
        'meta' => [
            'currentPage' => $this->currentPage(),
            'perPage' => $this->perPage(),
            'total' => $this->total(),
            'lastPage' => $this->lastPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'hasMorePages' => $this->hasMorePages(),
        ],
      ];
    endif;
    return [
      'rows' => $this->collection,
      'meta' => [
          'currentPage' => $this->currentPage(),
          'perPage' => $this->perPage(),
          'total' => $this->total(),
          'lastPage' => $this->lastPage(),
          'from' => $this->firstItem(),
          'to' => $this->lastItem(),
          'hasMorePages' => $this->hasMorePages(),
      ],
    ];
  }
}
