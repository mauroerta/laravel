<?php
namespace ME\Commands;

use Illuminate\Console\Command;

class TraitCommand extends Command
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
   * The path to the template
   * @var string
   */
  protected $template_path = __DIR__ . '/templates/trait.template';

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
    $folder = app_path("Traits");
    $path = $folder . '\\' . $name . '.php';
    $template = file_get_contents($this->template_path);

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
      $response = $this->ask("File {$path} exists, Do you want to override this file? (yes)");
      if(!in_array(strtolower($response), ['yes', 'y', '']))
        return;
    }

    $template = str_replace("{{name}}", $name, $template);

    $file = fopen($path, "w");

    if(!fwrite($file, $template)) {
      $this->error("Impossible to create the file: {$path}");
      return;
    }
    else {
      $this->info("Trait {$name} created.");
    }
  }
}
