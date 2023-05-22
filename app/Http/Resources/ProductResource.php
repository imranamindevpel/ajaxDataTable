<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'detail' => $this->detail,
            'action' => '<div class="btn-group">
            <button class="btn btn-success" data-toggle="modal" data-target="#view-create-edit" onclick="view('.$this->id.')">View</button>
            <button class="btn btn-info" data-toggle="modal" data-target="#view-create-edit" onclick="edit('.$this->id.')">Edit</button>
            <button class="btn btn-danger" onclick="destroy('.$this->id.')">Delete</button>
            </div>',
        ];
    }
}
