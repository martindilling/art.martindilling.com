<?php

namespace App\Service\Stripe;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Product implements Arrayable
{
    /** @var string|null */
    private $id = null;

    /** @var bool */
    private $active = true;

    /** @var string */
    private $name;

    /** @var string */
    private $type = 'good';

    /** @var string|null */
    private $caption = null;

    /** @var string|null */
    private $description = null;

    /** @var Images|null */
    private $images = null;

    /** @var PackageDimensions|null */
    private $packageDimensions = null;

    /** @var string|null */
    private $url = null;

    /** @var Carbon|null */
    private $created = null;

    /** @var Sku|null */
    private $sku = null;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function id() : string
    {
        return $this->id;
    }

    public function sku() : Sku
    {
        return $this->sku;
    }

    public function setId(?string $id) : void
    {
        $this->id = $id;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    public function setActive(?bool $active) : void
    {
        $this->active = $active;
    }

    public function setCaption(?string $caption) : void
    {
        $this->caption = $caption;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
    }

    public function setImages(?Images $images) : void
    {
        $this->images = $images;
    }

    public function setPackageDimensions(?PackageDimensions $packageDimensions) : void
    {
        $this->packageDimensions = $packageDimensions;
    }

    public function setUrl(?string $url) : void
    {
        $this->url = $url;
    }

    public function setCreated(?Carbon $created) : void
    {
        $this->created = $created;
    }

    public function setSku(?Sku $sku) : void
    {
        $this->sku = $sku;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() : array
    {
        return array_filter([
//            'id' => $this->id,
            'active' => $this->active,
            'name' => $this->name,
//            'type' => $this->type,
            'caption' => $this->caption,
            'description' => $this->description,
            'images' => $this->images ? $this->images->toArray() : null,
            'package_dimensions' => $this->packageDimensions ? $this->packageDimensions->toArray() : null,
            'url' => $this->url,
//            'created' => $this->created ? (int) $this->created->format('U') : null,
        ], function ($value) { return $value !== null; });
    }
}
