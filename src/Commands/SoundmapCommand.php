<?php

namespace CodiceOvvio\Soundmap\Commands;

use Illuminate\Console\Command;

class SoundmapCommand extends Command
{
    public $signature = 'soundmap';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
