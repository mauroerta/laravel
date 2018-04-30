<?php
namespace ME\Commands;

use Illuminate\Console\Command;

class ObserverCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:observer {name : The name of the Observe} {--o|observe=user : The class you want to observe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer';

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
        $observe = $this->option('observe') ? $this->option('observe') : 'App\User';
        $folder = app_path("Observers");
        $path = $folder . '\\' . $name . '.php';
        $template = file_get_contents('templates/observer.template');

        $params = [
            'name' => $name,
            'observe' => $observe,
            'class' => end(explode('\\', $observe)),
            'object' => '$'.strotolower(end(explode('\\', $observe)))
        ];

        foreach ($params as $key => $value) {
            $template = str_replace("{{{$key}}}", $value, $template);
        }

        if(!file_exists($folder)) {
            if(!mkdir($folder)) {
                $this->error("Impossible to create the folder: {$folder}");
                return;
            }
            else {
                $this->info("Folder {$folder} created.");
            }
        }

        if(file_exists($path)) {
            $response = $this->confirm("File {$path} exists, Do you want to override this file? (no)");
            if(!$response)
                return;
        }

        $file = fopen($path, "w");

        if(!fwrite($file, $template)) {
            $this->error("Impossible to create the file: {$path}");
            return;
        }
        else {
            $this->info("Observer {$name} created.");
            $this->info("Remember to add this line in the boot method of the AppServiceProvider:");
            $this->info("{$params['class']}::observe({$params['name']}::class);");
        }
    }
}
