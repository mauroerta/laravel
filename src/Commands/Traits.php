<?php

namespace Mauro\Commands;

use Illuminate\Console\Command;

class Traits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name : The name of the Trait}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $template = "<?php\n\nnamespace App\Traits;\n\ntrait {$name}\n{\n\t// Your code\n}";
        $folder = app_path("Traits");
        if(!file_exists($folder)) {
            if(!mkdir($folder)) {
                $this->error(__("Impossible to create the folder: {$folder}"));
                return;
            }
            else {
                $this->info(__("Folder {$folder} created."));
            }
        }

        $path = $folder . '\\' . $name . '.php';

        if(file_exists($path)) {
            $response = $this->ask(__("File {$path} exists, Do you want to override this file? (yes)"));
            if(!in_array(strtolower($response), ['yes', 'y', '']))
                return;
        }

        $file = fopen($path, "w");

        if(!fwrite($file, $template)) {
            $this->error(__("Impossible to create the file: {$path}"));
            return;
        }
        else {
            $this->info(__("Trait {$name} created."));
        }
    }
}
