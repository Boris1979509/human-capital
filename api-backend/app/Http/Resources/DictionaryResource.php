<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DictionaryResource extends JsonResource
{
    public function toArray($request): array
    {
        $return = array();
        foreach ($this as $key => $val) {
            foreach ($val as $dic) {
                $return[] = [
                    'id' => $dic->id,
                    'slug' => $dic->slug,
                    'name' => $dic->option,
                    'alternative' => $dic->alternative,
                    'approved' => $dic->approved,
                ];
            }
        }
        return $return;
    }
}
