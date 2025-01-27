<?php

namespace App\Http\Resources\Api;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'total'         => (int)($this -> total() ?? 0),
            'count'         => (int)($this -> count() ?? 0),
            'per_page'      => (int)($this -> perPage() ?? 0),
            'next_page_url' => $this -> nextPageUrl() ?? '',
            'perv_page_url' => $this -> previousPageUrl() ?? '',
            'current_page'  => (int)($this -> currentPage() ?? 0),
            'total_pages'   => (int)($this -> lastPage() ?? 0),
        ];
    }
}
