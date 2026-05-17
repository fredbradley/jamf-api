<?php

declare(strict_types=1);

namespace Cranleigh\JamfApi\Resources;

/**
 * Icons — upload and retrieve icons used in Jamf Pro.
 *
 * Required privilege: Create Categories (for upload)
 */
class IconsResource extends AbstractResource
{
    /**
     * Upload a new icon image.
     *
     * @param  string  $filePath  Local path to the image file (PNG or JPG).
     * @return array{id: int, url: string}  The uploaded icon ID and URL.
     */
    public function upload(string $filePath): array
    {
        return $this->http->postMultipart('/v1/icon', [
            ['name' => 'file', 'contents' => fopen($filePath, 'r'), 'filename' => basename($filePath)],
        ])->json();
    }

    /**
     * Download an icon by ID.
     *
     * Returns the raw binary image content.
     *
     * @param  int   $id    Icon ID.
     * @param  bool  $size  Whether to download the full-size (320px) version.
     */
    public function download(int $id, bool $size = false): string
    {
        $path = $size ? "/v1/icon/download/{$id}?res=size" : "/v1/icon/download/{$id}";

        return $this->http->get($path)->body();
    }
}
