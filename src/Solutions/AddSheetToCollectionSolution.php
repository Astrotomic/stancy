<?php

namespace Astrotomic\Stancy\Solutions;

use Facade\IgnitionContracts\RunnableSolution;
use Illuminate\Contracts\Filesystem\Filesystem;
use ReflectionClass;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Repositories\FilesystemRepository;

class AddSheetToCollectionSolution implements RunnableSolution
{
    /** @var string */
    protected $collection;

    /** @var string */
    protected $sheet;

    /** @var Filesystem|null */
    protected $filesystem;

    /** @var string|null */
    protected $extension;

    public function __construct(string $collection, string $sheet)
    {
        $this->collection = $collection;
        $this->sheet = $sheet;

        $this->copyPropertiesFromFilesystemRepository();
    }

    public function getSolutionTitle(): string
    {
        return 'The sheet is missing';
    }

    public function getSolutionDescription(): string
    {
        return "Add sheet `{$this->sheet}` to collection `{$this->collection}`.";
    }

    public function getRunButtonText(): string
    {
        return 'Add sheet file';
    }

    public function getSolutionActionDescription(): string
    {
        return 'Pressing the button below will try to add the sheet file to the collection filesystem.';
    }

    public function getRunParameters(): array
    {
        return [];
    }

    public function getDocumentationLinks(): array
    {
        return [];
    }

    public function run(array $parameters = []): bool
    {
        if ($this->filesystem === null) {
            return false;
        }

        if ($this->extension === null) {
            return false;
        }

        return $this->filesystem->put($this->sheet.'.'.$this->extension, '');
    }

    protected function copyPropertiesFromFilesystemRepository(): void
    {
        $repository = Sheets::collection($this->collection);

        if (! $repository instanceof FilesystemRepository) {
            return;
        }

        $repositoryReflection = new ReflectionClass($repository);

        $filesystemProperty = $repositoryReflection->getProperty('filesystem');
        $filesystemProperty->setAccessible(true);
        $this->filesystem = $filesystemProperty->getValue($repository);

        $extensionProperty = $repositoryReflection->getProperty('extension');
        $extensionProperty->setAccessible(true);
        $this->extension = $extensionProperty->getValue($repository);
    }
}
