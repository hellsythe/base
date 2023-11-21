<?php

namespace Sdkconsultoria\Core\Fields;

class FileField extends Field
{
    public $component = 'FileField';

    protected $disk = '';
    protected $folder = 'media';

    public function setDisk(string $folder, string $disk = 'public'): self
    {
        $this->folder = $folder;
        $this->disk = $disk;
        return $this;
    }

    public function getField(): array
    {
        return array_merge([
            'folder' => $this->folder,
            'disk' => $this->disk
        ], parent::getField());
    }
}
