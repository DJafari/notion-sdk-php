<?php

namespace Notion\Blocks;

use Notion\Blocks\Exceptions\BlockTypeException;
use Notion\Common\File;
use Notion\NotionException;

/**
 * @psalm-import-type BlockJson from Block
 * @psalm-import-type FileJson from \Notion\Common\File
 *
 * @psalm-type PdfJson = array{ pdf: FileJson }
 *
 * @psalm-immutable
 */
class Pdf implements BlockInterface
{
    private const TYPE = Block::TYPE_PDF;

    private Block $block;

    private File $file;

    private function __construct(Block $block, File $file)
    {
        if (!$block->isPdf()) {
            throw new BlockTypeException(self::TYPE);
        }

        $this->block = $block;
        $this->file = $file;
    }

    public static function create(File $file): self
    {
        $block = Block::create(self::TYPE);

        return new self($block, $file);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var BlockJson $array */
        $block = Block::fromArray($array);

        /** @psalm-var PdfJson $array */
        $file = File::fromArray($array[self::TYPE]);

        return new self($block, $file);
    }

    public function toArray(): array
    {
        $array = $this->block->toArray();

        $array[self::TYPE] = $this->file->toArray();

        return $array;
    }

    public function block(): Block
    {
        return $this->block;
    }

    public function file(): File
    {
        return $this->file;
    }

    public function withFile(File $file): self
    {
        return new self($this->block, $file);
    }

    public function changeChildren(array $children): self
    {
        throw new NotionException(
            "This block does not support children.",
            "no_children_support",
        );
    }
}
