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
    protected $signature = 'make:observer {name : The name of the Observer} {--o|observe=App\\User : The class you want to observe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer';

    /**
     * The path to the template
     * @var string
     */
    protected $template_path = __DIR_ . '/templates/observer.template';

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
     * @return void
     */
    public function handle()
    {
        $name = $this->argument('name');
        $observe = $this->option('observe') ? $this->option('observe') : 'App\\User';
        $class_parts = explode('\\', $observe);
        $class = end($class_parts);
        $folder = app_path("Observers");
        $path = $folder . '\\' . $name . '.php';
        $template = file_get_contents($this->template_path);

        $params = [
            'name' => $name,
            'observe' => $observe,
            'class' => $class,
            'object' => '$'.strtolower($class)
        ];

        $template = me_replace($template, $params);

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
            $response = $this->confirm("File {$path} exists, Do you want to override this file?", 'yes');
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
            $this->info("Remember, add this lines of code in the boot method of the AppServiceProvider:");
            $this->info("use App\\Observers\\{$name}");
            $this->info("{$class}::observe({$name}::class);");
        }
    }
}
