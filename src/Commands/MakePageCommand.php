<?php

namespace Astrotomic\Stancy\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Repositories\FilesystemRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Contracts\Filesystem\Factory as FilesystemManagerContract;

class MakePageCommand extends GeneratorCommand
{
    protected $name = 'make:page';

    protected $description = 'Create a new page for stancy package.';

    /** @var ConfigContract */
    protected $config;

    /** @var FilesystemManagerContract */
    protected $filesystemManager;

    public function __construct(Filesystem $files, ConfigContract $config, FilesystemManagerContract $filesystemManager)
    {
        parent::__construct($files);

        $this->config = $config;
        $this->filesystemManager = $filesystemManager;
    }

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
            $this->warn('can not create a sheet if collection is not instance of `'.FilesystemRepository::class.'`'); // @codeCoverageIgnore

            return; // @codeCoverageIgnore
        }

        $extension = $this->config->get('sheets.collections.'.$collection.'.extension', 'md');
        $disk = $this->config->get('sheets.collections.'.$collection.'.disk', $collection);
        $filesystem = $this->filesystemManager->disk($disk);

        $filename = Str::kebab(class_basename($name)).'.'.$extension;

        if (
            (! $this->hasOption('force') || ! $this->option('force'))
            && $filesystem->exists($filename)
        ) {
            $this->error('the sheet `'.$filename.'` already exists');

            return;
        }

        $filesystem->put($filename, $this->getDefaultSheetContent($extension));
    }

    protected function getDefaultSheetContent(string $extension): string
    {
        if (! in_array($extension, ['md', 'json', 'yaml', 'yml'])) {
            return '';
        }

        $data = [
            '_pageData' => '\\'.$this->qualifyClass($this->getNameInput()),
        ];

        if ($extension === 'json') {
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
        return __DIR__.'/../../resources/stubs/page.stub';
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
