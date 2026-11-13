<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function store(
        Property $property,
        ?array $files,
        int $coverIndex = 0
    ): void {
        if (empty($files)) {
            return;
        }

        foreach (array_values($files) as $i => $file) {
            $path = $file->store(
                "properties/{$property->property_id}",
                'public'
            );

            $property->image()->create([
                'image_path' => $path,
                'is_cover' => $i === $coverIndex,
                'sort_order' => $i,
            ]);
        }
    }

    public function deleteAll(Property $property): void
    {
        $property->images()->get()->each(function ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        });
    }
}