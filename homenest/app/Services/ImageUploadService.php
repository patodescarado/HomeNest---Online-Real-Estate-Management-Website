<?php
class ImageUploadService
{
    public function store(Property $property, array $files, int $coverIndex = 0): void
    {
        foreach (array_values($files) as $i => $file) {
            $path = $file->store("properties/{$property->id}", 'public');
            $property->images()->create([
                'image_path' => $path,
                'is_cover'   => $i === $coverIndex,
                'sort_order' => $i,
            ]);
        }
    }

    public function deleteAll(Property $property): void
    {
        Storage::disk('public')->deleteDirectory("properties/{$property->id}");
    }
}