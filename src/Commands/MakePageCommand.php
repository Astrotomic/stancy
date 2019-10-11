<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;

class MakePageCommand extends GeneratorCommand
{
    protected $name = 'make:page';

    protected $description = 'Create a new page for stancy package.';

    public function handle()
    {
        $this->createSheet();

        return parent::handle();
    }

    protected function createSheet(): void
    {
        $name = $this->getNameInput();
        $collection = $this->option('collection');

        if (! $collection) {
            return;
        }

        $repository = Sheets::collection($collection);

        if (! $repository instanceof FilesystemRepository) {
            $this->warn('can not create a sheet if collection is not instance of `'.FilesystemRepository::class.'`');

            return;
        }

        $filename = Str::kebab(class_basename($name)).'.'.$repository->getExtension();

        if (
            (! $this->hasOption('force') || ! $this->option('force'))
            && $repository->getFilesystem()->exists($filename)
        ) {
            $this->error('the sheet `'.$filename.'` already exists');

            return;
        }

        $repository->getFilesystem()->put($filename, $this->getDefaultSheetContent($repository->getExtension()));
    }

    protected function getDefaultSheetContent(string $extension): string
    {
        if (! in_array($extension, ['md', 'json', 'yaml', 'yml'])) {
            return '';
        }

        $data = [
            '_pageData' => '\\'.$this->qualifyClass($this->getNameInput()),
        ];

        if ($extension == 'json') {
            return json_encode($data);
        }

        if (in_array($extension, ['yaml', 'yml'])) {
            return Yaml::dump($data);
        }

        return implode(PHP_EOL, [
            '---',
            trim(Yaml::dump(array_merge($data, ['_view' => null]))),
            '---',
            '',
        ]);
    }

    protected function getStub(): string
    {
        return __DIR__.'/../../../../stancy/resources/stubs/page.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return rtrim($rootNamespace, '\\').'\Pages';
    }

    protected function replaceClass($stub, $name): string
    {
        return parent::replaceClass($stub, $name);
    }

    public function getOptions()
    {
        return [
            ['collection', null, InputOption::VALUE_REQUIRED, 'The sheet collection to create the page in'],
        ];
    }
}
