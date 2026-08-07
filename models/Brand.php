<?php

class Brand
{
    public ?int $id = null;
    public string $brandname;
    public string $slug;
    public ?string $image = null;
    public ?string $description = null;
    public int $status;
    public ?string $created_at = null;

    public ?string $updated_at = null;


    public function __construct(
        string $brandname = "",
        string $slug = "",
        ?string $image = null,
        ?string $description = null,
        int $status = 1
    ) {
        $this->brandname = $brandname;
        $this->slug = $slug;
        $this->image = $image;
        $this->description = $description;
        $this->status = $status;
    }
}